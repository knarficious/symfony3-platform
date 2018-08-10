<?php


// src/Knarf/PlatformBundle/Controller/AdvertController.php


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

class AdvertController extends Controller

{

    public function indexAction()

    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Advert');
        $listAdverts = $repository->afficherDerniersArticles();
        
        return $this->render('KnarfPlatformBundle:Advert:index.html.twig', array(
        'listAdverts' => $listAdverts
        ));

    }
    
    public function viewAction($id, Request $request)
    {
       
        $repository1 = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfPlatformBundle:Advert');

        $advert = $repository1->find($id);
        
                    if(null === $advert)
            {
                throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas!");
            }
      $commentaire = new Commentaire();
      $user = $this->getUser();
      $commentaire->setUser($user);
      $commentaire->setAdvert($advert);
      
      $form = $this->createForm(CommentaireType::class, $commentaire);
      
      if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
      {
            $em = $this->getDoctrine()->getManager();
            //$em->persist($advert);
            $em->persist($commentaire);
            $em->flush();
            
             $this->addFlash('notice', 'Commentaire ajouté avec succès.');
      }

        return $this->render('KnarfPlatformBundle:Advert:view.html.twig', array(

        'advert' => $advert, 'active' => 'advert', 'commentaire' => $commentaire, 'form' => $form->createView()

        ));
	
			
	//return $this->redirectToRoute('knarf_platform_home');
    }
    
    public function addAction(Request $request)
    {        
        $advert = new Advert();
        
        $user = $this->getUser();
        $advert->setUser($user);
        $advert->setDate(new \DateTime());
        $advert->setUpDateAt(new \DateTime());
        $form = $this->createForm(AdvertType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                      
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $this->addFlash('notice', 'Annonce bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce

            return $this->redirect($this->generateUrl('knarf_platform_view', array('id' => $advert->getId())));
           
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
    
    return $this->render('KnarfPlatformBundle:Advert:ajout.html.twig', array('form' => $form->createView()));
    
    }
  
    public function editAction($id, Request $request)
    {
          
        $em = $this->getDoctrine()->getManager();    
        $advert = $em->getRepository('KnarfPlatformBundle:Advert')->find($id);
        
        $advert->setUpDateAt(new \DateTime());
        
        //On vérifie si l'annonce appartient à l'utilisateur en cours
        if($this->getUser() === $advert->getUser())
        {
            if(null === $advert)
            {
                throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas!");
            }
    
            $form = $this->createForm(AdvertEditType::class, $advert);
    
            if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
            {                
                $em->persist($advert);
                $em->flush();
        
                $this->addFlash('notice', 'Annonce modifiée avec succès.');
        
            return $this->redirectToRoute('knarf_platform_view', array('id' => $advert->getId()));
            }


            return $this->render('KnarfPlatformBundle:Advert:edit.html.twig', array('advert' => $advert,
            'form' => $form->createView()));
        }
        
        else
        {            
            return $this->redirectToRoute('knarf_platform_view', array('id' => $advert->getId()));
        }    


    }


  public function deleteAction($id, Request $request)

  {

    $em = $this->getDoctrine()->getManager();
    
    $advert = $em->getRepository('KnarfPlatformBundle:Advert')->find($id);
    
    if($this->getUser() === $advert->getUser())
    {
        if(null === $advert)
        {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas!");
        }
    
        $form = $this->createFormBuilder()->getForm();
    
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em->remove($advert);
            $em->flush();
        
            $this->addFlash('notice', "L'annonce a été supprimée avec succès");
        
            return $this->redirect($this->generateUrl('knarf_platform_home'));    
        }

        return $this->render('KnarfPlatformBundle:Advert:delete.html.twig', array(
        'advert'    => $advert,
        'form'      => $form->createView()
        ));
    }
     
    else
        {            
            return $this->redirectToRoute('knarf_platform_view', array('id' => $advert->getId()));
        }
    

  }
  
  public function menuAction()

  {

    $listAdverts = $this->getDoctrine()
            ->getRepository(Advert::class)
            ->afficherDerniersArticles();


    return $this->render('KnarfPlatformBundle:Advert:menu.html.twig', array(

      'listAdverts' => $listAdverts

    ));

  }
  
  

}
