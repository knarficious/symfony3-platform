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
        $listAdverts = $repository->findAll();
        
        return $this->render('KnarfPlatformBundle:Advert:index.html.twig', array(
        'listAdverts' => $listAdverts
        ));

    }
    
    public function viewAction(Advert $advert, $id, Request $request)
    {
       $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfPlatformBundle:Advert');

        $advert = $repository->find($id);
        
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
      }

        return $this->render('KnarfPlatformBundle:Advert:view.html.twig', array(

        'advert' => $advert, 'active' => 'advert', 'form' => $form->createView()

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
            
            var_dump($advert->getDate());
            var_dump($advert->getUpDateAT());
            var_dump($advert->getUser()->getUsername());
            var_dump($advert->getRubrique()->getIntitule());
            var_dump($advert->getNomMedia());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

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
                $em->flush();
        
                $request->getSession()->getFlashBag()->add('notice', 'Annonce modifiée avec succès.');
        
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
    
        if($form->handleRequest($request)->isValid())
        {
            $em->remove($advert);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('notice', "L'annonce a été supprimée avec succès");
        
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

    $repository = $this->getDoctrine()->getManager()->getRepository('KnarfPlatformBundle:Advert');
    $listAdverts = $repository->findAll();


    return $this->render('KnarfPlatformBundle:Advert:menu.html.twig', array(

      // Tout l'intérêt est ici : le contrôleur passe

      // les variables nécessaires au template !

      'listAdverts' => $listAdverts

    ));

  }
  
  

}
