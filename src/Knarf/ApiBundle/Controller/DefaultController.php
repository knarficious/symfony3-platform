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
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;


/**
 * Description of DefaultController
 *
 * @author franck
 */
class DefaultController extends Controller 
{
    /**
     * @ApiDoc(description="Récupère la liste des articles par auteur")
     * 
     * @Rest\View(serializerGroups={"advert"})
     * @Rest\Get("/users/{id}/adverts")
     */
    public function getAdvertsByUsersAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('KnarfUserBundle:User')
                ->find($request->get('id')); // L'identifiant en tant que paramétre n'est plus nécessaire
        /* @var $user Place */

        if (empty($user)) {
            return $this->userNotFound();
        }

        return $user->getAdverts();
        
    }
    
    /**
     * @ApiDoc(description="Récupère la liste des articles")
     * 
     * @Rest\View(serializerGroups={"advert"})
     * @Rest\Get("/adverts")
     */
    public function getAdvertsAction(Request $request)
    {
                $adverts = $this->get('doctrine.orm.entity_manager')
                ->getRepository('KnarfPlatformBundle:Advert')
                ->findAll();
        
        // Création d'une vue FOSRestBundle
//        $view = View::create($adverts);
//        $view->setFormat('json');

        // Gestion de la réponse
        return $adverts;
    }

    
    /**
     * @ApiDoc(description="Récupère la liste des utilisateurs")
     * 
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users")
     * @Rest\QueryParam(name="offset", requirements="\d+", default="", description="Index de début de la pagination")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="", description="Nombre d'éléments à afficher")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, description="Ordre de tri (basé sur le nom)")
     */
    public function GetUsersAction(Request $request, ParamFetcher $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $sort = $paramFetcher->get('sort');
        
        $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
        
        $qb->select('u')
           ->from('KnarfUserBundle:User', 'u');
        
        if ($offset != "") {
            $qb->setFirstResult($offset);
        }

        if ($limit != "") {
            $qb->setMaxResults($limit);
        }

        if (in_array($sort, ['asc', 'desc'])) {
            $qb->orderBy('u.username', $sort);
        }                
        
        $users  = $qb->getQuery()->getResult();
        
        // Création d'une vue FOSRestBundle
//        $view = View::create($users);
//        $view->setFormat('json');

        // Gestion de la réponse
        return $users;
    }
    
    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}
