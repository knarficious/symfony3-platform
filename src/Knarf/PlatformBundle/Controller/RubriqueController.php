<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knarf\PlatformBundle\Form\RubriqueType;
use Knarf\PlatformBundle\Entity\Rubrique;

/**
 * Description of RubriqueController
 *
 * @author franck
 */
class RubriqueController extends Controller{
    
    
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Rubrique');
        $listRubriques = $repository->findAll();
        
        return $this->render('KnarfPlatformBundle:Rubrique:index.html.twig', array(
        'listRubriques' => $listRubriques
        ));

    }
    
    public function viewAction($id)
    {
       $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfPlatformBundle:Rubrique');

        $rubrique = $repository->find($id);

        return $this->render('KnarfPlatformBundle:Rubrique:view.html.twig', array(

        'rubrique' => $rubrique

        ));
	
			
	//return $this->redirectToRoute('knarf_platform_home');
    }
    
    public function editAction($id, Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        
        $em = $this->getDoctrine()->getManager();
    
        $rubrique = $em->getRepository('KnarfPlatformBundle:Rubrique')->find($id);
    
        if(null === $rubrique)
        {
            throw new NotFoundHttpException("La rubrique d'id ".$id." n'existe pas!");
        }
    
        $form = $this->createForm(RubriqueType::class, $rubrique);
    
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('notice', 'Rubrique modifiée avec succès.');
        
            return $this->redirectToRoute('rubrique_view', array('id' => $rubrique->getId()));
        }


        return $this->render('KnarfPlatformBundle:Rubrique:edit.html.twig',
                array(
                    'rubrique' => $rubrique,
                    'form' => $form->createView())
                );

    }

    public function deleteAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
    
        $rubrique = $em->getRepository('KnarfPlatformBundle:Rubrique')->find($id);
    
        if(null === $rubrique)
        {
            throw new NotFoundHttpException("La rubrique d'id ".$id." n'existe pas!");
        }
    
        $form = $this->createFormBuilder()->getForm();
    
        if($form->handleRequest($request)->isValid())
        {
            $em->remove($rubrique);
            $em->flush();
        
        $request->getSession()->getFlashBag()->add('notice', "La rubrique a été supprimée avec succès");
        
        return $this->redirect($this->generateUrl('knarf_platform_home'));
    
        }

        return $this->render('KnarfPlatformBundle:Rubrique:delete.html.twig', array(
        'rubrique'    => $rubrique,
        'form'      => $form->createView()
        ));

    }
  
    public function menuAction()
    {

        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Rubrique');
        $listRubriques = $repository->findAll();


        return $this->render('KnarfPlatformBundle:Rubrique:menu.html.twig', array(

      // Tout l'intérêt est ici : le contrôleur passe

      // les variables nécessaires au template !

        'listRubriques' => $listRubriques

        ));

    }
    
    public function addAction(Request $request)
    {
        
        $rubrique = new Rubrique();
        
        $form = $this->createForm(RubriqueType::class, $rubrique);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rubrique);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Rubrique bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce

            return $this->redirect($this->generateUrl('rubrique_view', array('id' => $rubrique->getId())));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
    
    return $this->render('KnarfPlatformBundle:Rubrique:ajout.html.twig', array('form' => $form->createView()));
    
    }
    
}
