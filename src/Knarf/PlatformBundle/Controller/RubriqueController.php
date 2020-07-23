<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Knarf\PlatformBundle\Form\RubriqueType;
use Knarf\PlatformBundle\Form\RubriqueEditType;
use Knarf\PlatformBundle\Entity\Rubrique;
use Knarf\PlatformBundle\Entity\Advert;

/**
 * Description of RubriqueController
 *
 * @author franck
 */
class RubriqueController extends Controller {

    /**
     * @Route("/", name="knarf_platform_home")
     * @return type
     */
    public function indexAction() {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Rubrique');
        $listRubriques = $repository->getRubriquesEtAdverts();

        return $this->render('KnarfPlatformBundle:Rubrique:index.html.twig', array(
                    'listRubriques' => $listRubriques
        ));
    }

    /**
     * @Route("/rubriques", name="rubrique_ext_index")
     * @return type
     */
    public function extendedIndexAction() {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Rubrique');
        $listRubriques = $repository->getRubriques();

        return $this->render('KnarfPlatformBundle:Rubrique:index_ext.html.twig', array(
                    'listRubriques' => $listRubriques
        ));
    }

    /**
     * @Route("/rubrique/{slug}", name="rubrique_ext")
     * @param type $slug
     * @return type
     * @throws NotFoundHttpException
     */
    public function extendedViewAction($slug, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $rubrique = $em->getRepository('KnarfPlatformBundle:Rubrique')->findOneBy(['slug' => $slug]);
        $rubriqueId = $rubrique->getId();
        $advertsRep = $em->getRepository('KnarfPlatformBundle:Advert');
        $allAdvertsQuery = $advertsRep->createQueryBuilder('a')
                ->where('a.rubrique = :rubrique')
                ->andWhere('a.published = 1')
                ->andWhere('a.isAdmin = 0')
                ->setParameter('rubrique', $rubriqueId)
                ->getQuery();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $allAdvertsQuery,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 8),
                ['defaultSortFieldName' => 'a.date', 'defaultSortDirection' => 'desc']
        );
        $seoPage = $this->container->get('sonata.seo.page');
        
        $seoPage->addTitle($rubrique->getIntitule())
                ->addMeta('name', 'description', $rubrique->getIntitule())
                ->addMeta('property', 'og:locale', 'fr_FR')
                ->addMeta('property', 'og:title', $rubrique->getIntitule())
                ->addMeta('property', 'og:type', 'blog')
                ->addMeta('property', 'og:url',  $this->generateUrl('rubrique_ext', ['slug'=> $rubrique->getSlug()
                ], UrlGeneratorInterface::ABSOLUTE_URL))
                ->addMeta('property', 'og:description', $rubrique->getIntitule())
//                ->addMeta('property', 'og:image', $this->getParameter('base_url').$this->get('vich_uploader.templating.helper.uploader_helper')->asset($rubrique->getImage(), 'mediaFile'))
                ->addMeta('property', 'article:published_time', date_format($rubrique->getUpdateAt(), 'd/m/Y'))
                ->addMeta('property', 'twitter:card', 'summary')
                ->addMeta('property', 'twitter:title', $rubrique->getIntitule())
                ->addMeta('property', 'twitter:description', $rubrique->getIntitule())
//                ->addMeta('property', 'twitter:image', $this->getParameter('base_url').$this->get('vich_uploader.templating.helper.uploader_helper')->asset($rubrique->getImage(), 'mediaFile'))
                ->addMeta('property', 'twitter:url', $this->generateUrl('rubrique_ext', ['slug'=> $rubrique->getSlug()
                ], UrlGeneratorInterface::ABSOLUTE_URL))
                ;

        return $this->render('KnarfPlatformBundle:Rubrique:ext.html.twig', array('rubrique' => $rubrique, 'pagination' => $pagination));
    }

    public function viewAction($slug) {
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('KnarfPlatformBundle:Rubrique');

        $rubrique = $repository->findOneBy(array('slug' => $slug));

        if (null === $rubrique) {
            throw new NotFoundHttpException("La rubrique " . $slug . " n'existe pas!");
        }

        return $this->render('KnarfPlatformBundle:Rubrique:view.html.twig', array(
                    'rubrique' => $rubrique
        ));
    }

    /**
     * @Route("/rubrique/edit/{slug}", name="rubrique_edit")
     * @param type $slug
     * @param Request $request
     * @return type
     * @throws NotFoundHttpException
     */
    public function editAction($slug, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        $rubrique = $em->getRepository('KnarfPlatformBundle:Rubrique')->findOneBy(array('slug' => $slug));

        if (null === $rubrique) {
            throw new NotFoundHttpException("La rubrique " . $slug . " n'existe pas!");
        }

        $form = $this->createForm(RubriqueEditType::class, $rubrique);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $rubrique->setUpDateAt(new \DateTime());
            $em->persist($rubrique);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Rubrique modifiée avec succès.');

            return $this->redirectToRoute('rubrique_ext', array('slug' => $rubrique->getSlug()));
        }

        return $this->render('KnarfPlatformBundle:Rubrique:edit.html.twig',
                        [
                            'rubrique' => $rubrique,
                            'form' => $form->createView()
                        ]
        );
    }

    /**
     * @Route("/rubrique/delete/{slug}", name="rubrique_delete")
     * @param type $slug
     * @param Request $request
     * @return type
     * @throws NotFoundHttpException
     */
    public function deleteAction($slug, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $rubrique = $em->getRepository('KnarfPlatformBundle:Rubrique')->findOneBy(array('slug' => $slug));

        if (null === $rubrique) {
            throw new NotFoundHttpException("La rubrique " . $slug . " n'existe pas!");
        }

        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $em->remove($rubrique);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "La rubrique a été supprimée avec succès");

            return $this->redirect($this->generateUrl('knarf_platform_home'));
        }

        return $this->render('KnarfPlatformBundle:Rubrique:delete.html.twig', array(
                    'rubrique' => $rubrique,
                    'form' => $form->createView()
        ));
    }

    public function menuAction() {

        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Rubrique');
        $listRubriques = $repository->getRubriques();


        return $this->render('KnarfPlatformBundle:Rubrique:menu.html.twig', array(
                    'listRubriques' => $listRubriques
        ));
    }

    /**
     * @Route("/rubriques/ajout", name="rubrique_ajout")
     * @param Request $request
     * @return type
     */
    public function addAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $rubrique = new Rubrique();

        $rubrique->setUpdateAt(new \DateTime());

        $form = $this->createForm(RubriqueType::class, $rubrique);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rubrique);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Rubrique bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce

            return $this->redirect($this->generateUrl('rubrique_ext', array('slug' => $rubrique->getSlug())));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire

        return $this->render('KnarfPlatformBundle:Rubrique:ajout.html.twig', array('form' => $form->createView()));
    }

}
