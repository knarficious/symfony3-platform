<?php


// src/Knarf/PlatformBundle/Controller/CommentaireController.php


namespace Knarf\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        
        
        $comment = new Commentaire();
        $user = $this->getUser();
        $comment->setUser($user);
        $comment->setCommentaire($commentaire);
        
        $formulaire = $this->createForm(CommentaireType::class, $comment);
      
      if($request->isMethod('POST') && $formulaire->handleRequest($request)->isValid())
      {
            var_dump($comment->getDatePublication());
            var_dump($comment->getUser()->getUsername());
            var_dump($comment->getAdvert()->getTitle());
            var_dump($comment->getCommentaire()->getContenu());
            
          $em = $this->getDoctrine()->getManager();
            //$em->persist($advert);
            $em->persist($comment);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice', 'Commentaire bien enregistré.');
      }

        return $this->render('KnarfPlatformBundle:Commentaire:view.html.twig', array(

        'commentaire' => $commentaire, 'active' => 'commentaire', 'formulaire' => $formulaire->createView()

        ));
	
			
	//return $this->redirectToRoute('knarf_platform_home');
    }
    
    public function addAction(Request $request)
    {        
        $commentaire = new Commentaire();
        
        $user = $this->getUser();
        $commentaire->setUser($user);
        $commentaire->setDatePublication(new \DateTime());
        
        $formulaire = $this->createForm(CommentaireType::class, $commentaire);

        if ($request->isMethod('POST') && $formulaire->handleRequest($request)->isValid()) {
            
           
            //var_dump($commentaire->getUser()->getUsername());
            //var_dump($commentaire->getAdvert()->getTitle());
            var_dump($commentaire->getCommentaire()->getContenu());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Commentaire bien enregistré.');

            // Puis on redirige vers la page de visualisation de cettte annonce

            return $this->redirect($this->generateUrl('commentaire_view', array('id' => $commentaire->getId())));
           
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
    
    return $this->render('KnarfPlatformBundle:Commentaire:ajout.html.twig', array('formulaire' => $formulaire->createView()));
    
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
        
        //On vérifie si l'annonce appartient à l'utilisateur en cours
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
        
                $request->getSession()->getFlashBag()->add('notice', 'Commentaire modifié avec succès.');
        
            return $this->redirectToRoute('knarf_platform_view', array('id' => $advert->getId()));
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
        
            $this->addFlash('notice', "Le commentaire sur la publication ".$advert->getId()." a été supprimé avec succès");
             
                 return $this->redirectToRoute('knarf_platform_view', array('id' => $advert->getId()));
        
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
        
        $formulaire = $this->createForm(CommentaireType::class, $reponse);
      
      if($request->isMethod('POST') && $formulaire->handleRequest($request)->isValid())
      {
        var_dump($reponse->getDatePublication());
        var_dump($reponse->getUser()->getUsername());           
        var_dump($reponse->getCommentaire()->getContenu());
            
        $em = $this->getDoctrine()->getManager();
        $em->persist($reponse);
        $em->flush();
            
        $request->getSession()->getFlashBag()->add('notice', 'Commentaire bien enregistré.');
        

        return $this->redirectToRoute('knarf_platform_view', array('id' => $reponse->getCommentaire()->getAdvert()->getId()));
   
        
            
      }

        return $this->render('KnarfPlatformBundle:Commentaire:reponse.html.twig', array(

        'commentaire' => $commentaire, 'active' => 'commentaire', 'formulaire' => $formulaire->createView()

        ));			
	
    }
    
   

}
