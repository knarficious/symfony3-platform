<?php

// src/Knarf/PlatformBundle/Controller/AdvertController.php

namespace Knarf\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Knarf\PlatformBundle\Form\AdvertType;
use Knarf\PlatformBundle\Form\AdvertEditType;
use Knarf\PlatformBundle\Entity\Advert;
use Knarf\PlatformBundle\Entity\Media;

class AdvertController extends Controller {

    public function indexAction(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Advert');
        $listAdverts = $repository->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $listAdverts,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 6));

        return $this->render('KnarfPlatformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $pagination
        ));
    }

//    /**
//     * @Route("/annonces/{id}", name="knarf_platform_view")
//     * @ParamConverter("advert", class="KnarfPlatformBundle:Advert")
//     * @param Advert $advert
//     * @param Request $request
//     * @return Response
//     */
//    public function showAction(Advert $advert, Request $request)
//    {
//        $response = new Response();
//        
//        if ($response->isNotModified($request)) {
//            // envoie la réponse 304 tout de suite
//            return $response;
//        }
//        return $this->render('KnarfPlatformBundle:Advert:view.html.twig', ['advert' => $advert], $response);
//    }

    /**
     * @Route("/article/{slug}", name="knarf_platform_view")
     * @param type $slug
     * @param Request $request
     */
    public function viewAction($slug, Request $request) {

        $repository1 = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('KnarfPlatformBundle:Advert');

        $advert = $repository1->findOneBy(array('slug' => $slug));

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce " . $slug . " n'existe pas!");
        }

        return $this->render('KnarfPlatformBundle:Advert:view.html.twig', array(
                    'advert' => $advert, 'active' => 'advert'));
    }

    /**
     * @Route("/ajout", name="knarf_platform_add")
     * @param Request $request
     * @return type
     */
    public function addAction(Request $request) {
        $advert = new Advert();
        $media = new Media();
        $user = $this->getUser();
        $advert->setUser($user);
        $advert->setDate(new \DateTime());
        $advert->setUpDateAt(new \DateTime());
        $media->setDate(new \DateTime());
        $advert->setMedia($media);
        $form = $this->createForm(AdvertType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $media->setNomMedia($form->getData()->getMedia()->getNomMedia(), $form->getData()->getMedia()->getMediaFile());
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Annonce bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce

            return $this->redirect($this->generateUrl('knarf_platform_view', array('id' => $advert->getId(), 'slug' => $advert->getSlug())));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire

        return $this->render('KnarfPlatformBundle:Advert:ajout.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("modifier/{slug}", name="knarf_platform_edit")
     * @param type $slug
     * @param Request $request
     * @throws NotFoundHttpException
     */
    public function editAction($slug, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('KnarfPlatformBundle:Advert')->findOneBy(array('slug' => $slug));

        $advert->setUpDateAt(new \DateTime());

        //On vérifie si l'annonce appartient à l'utilisateur en cours
        if ($this->getUser() === $advert->getUser()) {
            if (null === $advert) {
                throw new NotFoundHttpException("L'annonce  " . $slug . " n'existe pas!");
            }

            $form = $this->createForm(AdvertEditType::class, $advert);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $em->persist($advert);
                $em->flush();

                $this->addFlash('success', 'Annonce modifiée avec succès.');

                return $this->redirectToRoute('knarf_platform_view', array('slug' => $advert->getSlug()));
            }


            return $this->render('KnarfPlatformBundle:Advert:edit.html.twig',
                            array('advert' => $advert, 'form' => $form->createView()));
        } else {
            return $this->redirectToRoute('knarf_platform_view', array('slug' => $advert->getSlug()));
        }
    }

    /**
     * @Route("/delete/{slug}", name="knarf_platform_delete")
     * @param type $slug
     * @param Request $request
     * @throws NotFoundHttpException
     */
    public function deleteAdvertAction($slug, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('KnarfPlatformBundle:Advert')->findOneBy(array('slug' => $slug));

        if ($this->getUser() === $advert->getUser()) {
            if (null === $advert) {
                throw new NotFoundHttpException("L'annonce  " . $slug . " n'existe pas!");
            }

            $form = $this->createFormBuilder()->getForm();

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $em->remove($advert);
                $em->flush();

                $this->addFlash('success', "L'annonce a été supprimée avec succès");

                return $this->redirect($this->generateUrl('profile'));
            }

            return $this->render('KnarfPlatformBundle:Advert:delete.html.twig', array(
                        'advert' => $advert,
                        'form' => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('knarf_platform_view', array('slug' => $advert->getSlug()));
        }
    }

    public function menuAction() {
        $listAdverts = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Advert')->getLastAdverts();

        return $this->render('KnarfPlatformBundle:Advert:menu.html.twig', array(
                    'listAdverts' => $listAdverts
        ));
    }

    public function getAdvertManager() {
        return $this->get('app.advert.manager');
    }

    public function getAdvertRepository() {
        return $this->get('app.advert.repository');
    }

}
