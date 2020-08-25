<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;
use Knarf\PlatformBundle\Entity\Advert;
use Knarf\ApiBundle\Type\ApiAdvertType;

/**
 * Description of DefaultController
 *
 * @author franck
 */
class ApiAdvertController extends Controller
{
    /**
     * @ApiDoc(description="Récupère la liste des articles par auteur")
     * @Rest\View(serializerGroups={"advert"})
     * @Rest\Get("/user/{slug}/adverts")
     */
    public function getAdvertsByUserAction(Request $request)
    {
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('KnarfUserBundle:App_User')
                ->findOneBy(array('slug' => $request->get('slug')));

        if (empty($user)) {
            return $this->userNotFound();
        }

        return $user->getAdverts();
        
    }
    
    /**
     * @ApiDoc(description="Récupère la liste des articles")
     * @Rest\View(serializerGroups={"advert"})
     * @Rest\Get("/adverts")
     */
    public function getAdvertsAction()
    {
        $adverts = $this->get('doctrine.orm.entity_manager')
        ->getRepository('KnarfPlatformBundle:Advert')
        ->findAll();

        return $adverts;
    }
    
    /**
     * @ApiDoc(description="Récupère un article")
     * @Rest\View(serializerGroups={"advert"})
     * @Rest\Get("/adverts/{slug}")
     */
    public function getAdvertAction(Request $request) {
        
        $advert = $this->getDoctrine()
                ->getManager()
                ->getRepository('KnarfPlatformBundle:Advert')
                ->findOneBy(['slug' => $request->get('slug')]);
        
        if (empty($advert)) {
            return $this->advertNotFound();
        }
        
        return $advert;        
    }
    
    /**
     * @ApiDoc(description="Récupère un article depuis son id")
     * @Rest\View(serializerGroups={"advert"})
     * @Rest\Get("/adverts/{id}")
     * @param Request $request
     */
    public function getAdvertIdAction(Request $request)
    {
        $advert = $this->getDoctrine()
                ->getManager()
                ->getRepository('KnarfPlatformBundle:Advert')
                ->findOneById(['id' => $request->get('id')]);
        
        if (empty($advert)) {
            return $this->advertNotFound();
        }
        
        return $advert; 
    }
    
    /**
     * @ApiDoc(description="édite un article")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Put("/adverts/{slug}")
     */
    public function putAdvertAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('KnarfPlatformBundle:Advert')
                ->findOneBy(array('slug' => $request->get('slug')));
        
        if(null === $advert)
        {
            return $this->advertNotFound();
        }
        
        if($advert->getUser() !== $this->getUser())
        {
            throw new UnauthorizedHttpException('cette ressource ne vous appartient pas');
        }
        
        $form = $this->createForm(ApiAdvertType::class, $advert);
        $form->submit($request->request->all());      
        
        
            if( $form->isValid() )
            {
                $advert->setTitle($request->get('title'));
                $advert->setContent($request->get('content'));
                $advert->setSlug($request->get('slug'));
                $advert->setRubrique($em->find('KnarfPlatformBundle:Rubrique', $request->get('rubrique')));            
                $advert->setUpDateAt(new \DateTime());
                $em->flush();

            return $advert;
            
            }        
            else {
                return $form;
            }
    }
    
    /**
     * @ApiDoc(
     * resource="/api/adverts",
     * description="Publie un article",
     * input={
     *  "class"="Knarf\ApiBundle\Type\ApiAdvertType"
     * },
     * status_code={
     *  201="Publication enregistrée",
     *  403="Validation errors"
     * },
     * )
     * @Rest\Post("/adverts")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function postAdvertAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        
        $user = $this->getUser();
        
        $advert = new Advert();
        
        $form = $this->createForm(ApiAdvertType::class, $advert);
        $form->submit($request->request->all());        
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            
            $advert->setContent($request->get('content'));
            $advert->setDate(new \DateTime());
            $advert->setPublished(true);
            $advert->setSlug($request->get('slug'));
            $advert->setTitle($request->get('title'));
            $advert->setUpDateAt(new \DateTime());
            $advert->setUser($user);
            $advert->setRubrique($em->find('KnarfPlatformBundle:Rubrique', $request->get('rubrique')));
            
            $em->persist($advert);
            $em->flush();
            
            $response = new Response();
            $response->headers->set('Location', $this->generateUrl('knarf_api_advert', array(
                'slug' => $advert->getSlug(),
                \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL
            )));
            
            return $response;
        }
        else 
        {
            return $form;
        }
        
    }
    
    /**
     * @ApiDoc(description="Supprime un article")
     * @Rest\Delete("/adverts/{slug}")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     */
    public function deleteAdvertAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);    
        
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('KnarfPlatformBundle:Advert')
                ->findOneBy(array('slug' => $request->get('slug')));
        
        if(null === $advert)
        {
            return $this->advertNotFound();
        }
        
        if($advert->getUser() !== $this->getUser())
        {
            throw new UnauthorizedHttpException('cette ressource ne vous appartient pas');
        }        
        
            $em->remove($advert);
            $em->flush();
            
            $response = new Response();
            $response->headers->set('Location', $this->generateUrl('knarf_api_advert', array(
                'id' => $advert->getId(),
                \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL
            )));
            
            return $response;
        

    }

    /** 
     * @ApiDoc(description="Récupère la liste des articles par rubrique") 
     * @Rest\View(serializerGroups={"advert"})
     * @Rest\Get("/rubrique/{slug}/adverts")
     * @param Request $request
     */
    public function getAdvertsByRubriqueAction(Request $request)
    {
        $rubrique = $this->getDoctrine()
                ->getManager()
                ->getRepository('KnarfPlatformBundle:Rubrique')
                ->findOneBy(array('slug' => $request->get('slug')));

        if (empty($rubrique)) {
            return $this->rubriqueNotFound();
        }

        return $rubrique->getAdverts();
    }
    
    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
    
    private function advertNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Advert not found'], Response::HTTP_NOT_FOUND);
    }
    
    private function rubriqueNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Rubrique not found'], Response::HTTP_NOT_FOUND);
    }
}
