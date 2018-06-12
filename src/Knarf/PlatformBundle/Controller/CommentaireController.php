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
                throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas!");
            }
        
        
        $comment = new Commentaire();
        $user = $this->getUser();
        $comment->setUser($user);
        $comment->setCommentaire($commentaire);
        
        $form = $this->createForm(CommentaireType::class, $comment);
      
      if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
      {
            $em = $this->getDoctrine()->getManager();
            //$em->persist($advert);
            $em->persist($comment);
            $em->flush();
      }

        return $this->render('KnarfPlatformBundle:Commentaire:vue.html.twig', array(

        'commentaire' => $commentaire, 'active' => 'commentaire', 'form' => $form->createView()

        ));
	
			
	//return $this->redirectToRoute('knarf_platform_home');
    }
    
    public function addAction(Request $request)
    {        
        $commentaire = new Commentaire();
        
        $user = $this->getUser();
        $commentaire->setUser($user);        
        
        $commentaire->setDate(new \DateTime());
        $form = $this->createForm(CommentaireType::class, $commentaire);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            var_dump($commentaire->getDate());
            var_dump($commentaire->getUser()->getUsername());
            var_dump($commentaire->getAdvert()->getTitle());
            var_dump($commentaire->getCommentaire()->getContenu());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Commentaire bien enregistré.');

            // Puis on redirige vers la page de visualisation de cettte annonce

            return $this->redirect($this->generateUrl('commentaire_view', array('id' => $commentaire->getId())));
           
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
    
    return $this->render('KnarfPlatformBundle:Commentaire:ajout.html.twig', array('form' => $form->createView()));
    
    }
  
    public function editAction($id, Request $request)
    {
          
        $em = $this->getDoctrine()->getManager();    
        $commentaire = $em->getRepository('KnarfPlatformBundle:Commentaire')->find($id);
        
        //On vérifie si l'annonce appartient à l'utilisateur en cours
        if($this->getUser() === $commentaire->getUser())
        {
            if(null === $commentaire)
            {
                throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas!");
            }
    
            $form = $this->createForm(AdvertEditType::class, $commentaire);
    
            if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
            {
                $em->flush();
        
                $request->getSession()->getFlashBag()->add('notice', 'Commentaire modifié avec succès.');
        
            return $this->redirectToRoute('commentaire_view', array('id' => $commentaire->getId()));
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
        
            $request->getSession()->getFlashBag()->add('notice', "Le commentaire a été supprimé avec succès");
        
            return $this->redirect($this->generateUrl('knarf_platform_home'));    
        }

        return $this->render('KnarfPlatformBundle:Commentaire:delete.html.twig', array(
        'commentaire'    => $commentaire,
        'form'      => $form->createView()
        ));
    }
     
    else
        {            
            return $this->redirectToRoute('commentaire_view', array('id' => $commentaire->getId()));
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

}
