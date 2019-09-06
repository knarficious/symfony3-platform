<?php


// src/Knarf/PlatformBundle/Controller/CommentaireController.php


namespace Knarf\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Knarf\PlatformBundle\Form\AdvertType;
use Knarf\PlatformBundle\Form\AdvertEditType;
use Knarf\PlatformBundle\Entity\Advert;
use Knarf\PlatformBundle\Entity\Rubrique;
use Knarf\PlatformBundle\Entity\Commentaire;
use Knarf\PlatformBundle\Form\CommentaireType;

class CommentaireController extends Controller

{

    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Commentaire');
        
        $listCommentaires = $repository->findAll();
        
        return $this->render('KnarfPlatformBundle:Commentaire:index.html.twig', array(
        'listCommentaires' => $listCommentaires
        ));

    }   
    
    
    public function viewAction($id, Request $request)
    {
       $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfPlatformBundle:Commentaire');

        $commentaire = $repository->find($id);
        
                            if(null === $commentaire)
            {
                throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas!");
            }        

        return $this->render('KnarfPlatformBundle:Commentaire:view.html.twig', array(

        'commentaire' => $commentaire, 'active' => 'commentaire'));
    }
    
    /**
     * @Route("/commenter/{slug}", name="commentaire_add", condition="request.isXmlHttpRequest()")
     * @param type $slug
     * @param Request $request
     * @return Response
     */
    public function addAction($slug, Request $request)
    {        
        $repository1 = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfPlatformBundle:Advert');

        $advert = $repository1->findOneBy(array('slug' => $slug));
        
        if(null === $advert)
            {
                throw new NotFoundHttpException("L'annonce ".$slug." n'existe pas!");
            }
        
        $commentaire = new Commentaire();
        
        $user = $this->getUser();
        $commentaire->setUser($user);
        $commentaire->setAdvert($advert);
        $commentaire->setDatePublication(new \DateTime());
        
        $formulaire = $this->createForm(
                CommentaireType::class,
                $commentaire,
                array('action' => $this->generateUrl($request->get('_route'),
                        array('slug' => $slug)))
                                        );

        if ($formulaire->handleRequest($request)->isValid())
        {   
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Commentaire bien enregistré.');

            return new Response('success');
        }
        
        return $this->render('KnarfPlatformBundle:Commentaire:form.html.twig', array('form' => $formulaire->createView()));
    
    }
  
    public function editAction($id, Request $request)
    {
          
        $em = $this->getDoctrine()->getManager();    
        $commentaire = $em->getRepository('KnarfPlatformBundle:Commentaire')->find($id);
        $commentaire->setDateMiseAJour(new \DateTime('now'));
        
        $advert = null;
        
        if($commentaire->getAdvert() != null)
        {
            $advert = $commentaire->getAdvert();
        }
        
        else
        {
            $advert = $commentaire->getCommentaire()->getAdvert();
        }
        
        //On vérifie si le commentaire appartient à l'utilisateur en cours
        if($this->getUser() === $commentaire->getUser())
        {
            if(null === $commentaire)
            {
                throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas!");
            }
    
            $form = $this->createForm(CommentaireType::class, $commentaire);
    
            if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
            {
                $em->flush();
        
                $request->getSession()->getFlashBag()->add('success', 'Commentaire modifié avec succès.');
        
            return $this->redirectToRoute('knarf_platform_view', array('slug' => $advert->getSlug()));
            }


            return $this->render('KnarfPlatformBundle:Commentaire:edit.html.twig', array('commentaire' => $commentaire,
            'form' => $form->createView()));
        }
        
        else
        {            
            return $this->redirectToRoute('commentaire_view', array('id' => $commentaire->getId()));
        }    


    }


  public function deleteAction($id, Request $request)

  {
    $em = $this->getDoctrine()->getManager();
    
    $commentaire = $em->getRepository('KnarfPlatformBundle:Commentaire')->find($id);
    
    $advert = null;
    
    if ($commentaire->getAdvert() != null)
    {
        $advert = $commentaire->getAdvert();
    }
    
    else
    {
        $advert = $commentaire->getCommentaire()->getAdvert();    }
    
    
    if($this->getUser() === $commentaire->getUser())
    {
        if(null === $commentaire)
        {
            throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas!");
        }
    
        $form = $this->createFormBuilder()->getForm();
    
        if($form->handleRequest($request)->isValid())
        {
            $em->remove($commentaire);
            $em->flush();   
        
            $this->addFlash('success', "Le commentaire sur la publication ".$advert->getSlug()." a été supprimé avec succès");
             
                 return $this->redirectToRoute('knarf_platform_view', array('slug' => $advert->getSlug()));
        
        }

        return $this->render('KnarfPlatformBundle:Commentaire:delete.html.twig', array(
        'commentaire'    => $commentaire,
        'form'      => $form->createView()
        ));
    }
     
    else
        {            
            return $this->redirectToRoute('knarf_platform_view', array('id' => $commentaire->getAdvert()->getId()));
        }
    

  }
  
  public function menuAction()
  {

    $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Commentaire');
    $listCommentaires = $repository->findAll();


    return $this->render('KnarfPlatformBundle:Commentaire:menu.html.twig', array(

      // Tout l'intérêt est ici : le contrôleur passe

      // les variables nécessaires au template !

      'listCommentaires' => $listCommentaires

    ));

  }
  
  /**
   * @Route("/reply/{id}", name="commentaire_repond", condition="request.isXmlHttpRequest()")
   * @param type $id
   * @param Request $request
   * @return type
   * @throws NotFoundHttpException
   */  
  public function repondAction($id, Request $request)
    {
       $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfPlatformBundle:Commentaire');

        $commentaire = $repository->find($id);
        
                            if(null === $commentaire)
            {
                throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas!");
            }        
        
        $reponse = new Commentaire();
        $user = $this->getUser();
        //$advert = $commentaire->getAdvert();
        $reponse->setUser($user);
        //$reponse->setAdvert($advert);
        $reponse->setCommentaire($commentaire);
        
        $formulaire = $this->createForm(CommentaireType::class, $reponse,
                                        array('action' => $this->generateUrl($request->get('_route'), array('id' => $id)))
                                        );
      
      if($request->isMethod('POST') && $formulaire->handleRequest($request)->isValid())
      {
            
        $em = $this->getDoctrine()->getManager();
        $em->persist($reponse);
        $em->flush();
            
        $request->getSession()->getFlashBag()->add('success', 'Commentaire bien enregistré.');        

        return new Response('success');            
      }

        return $this->render('KnarfPlatformBundle:Commentaire:formulaire.html.twig', array(

        'commentaire' => $commentaire, 'formulaire' => $formulaire->createView()

        ));			
	
    }
    
   

}
