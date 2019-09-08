<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;
use Knarf\UserBundle\Entity\Registration\Registration;
use Knarf\ApiBundle\Type\ApiRegistrationType;
use Knarf\ApiBundle\Type\ApiEditEmailType;
use Knarf\UserBundle\Entity\User\Profile;

/**
 * Description of PutController
 *
 * @author franck
 */
class UserController extends Controller
{
    /**
     * @ApiDoc(
     * resource="/api/users",
     * description="Crée un compte",
     * input={
     *  "class"="Knarf\ApiBundle\Type\ApiRegistrationType"
     * },
     * status_code={
     *  201="Compte créé",
     *  403="Validation errors"
     * },
     * )
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users")
     */
    public function postUserAction(Request $request)
    {
        $user = new Registration();
        $form = $this->createForm(ApiRegistrationType::class, $user);
        
        if ($this->getRegistrationFormHandler()->handle($form, $request))
        {   
            return $user;
        }
        else {
            return $form;
        }
    }

    /**
     * @ApiDoc(description="Met à jour ...") 
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Put("/users/{id}")
     */
    public function putUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @ApiDoc(
     * description="Met à jour l'attribut email",
     * resource="/api/users/{slug}",
     * input={
     *  "class"="Knarf\ApiBundle\Type\ApiEditEmailType"
     * },
     * output= {
     *       "class"="Knarf\UserBundle\Entity\User\Profile",
     *       "groups"={"user"},
     *       "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     * },
     * status_codes={
     *      204="Mise à jour effectuée",
     *      403="Validation errors"
     * })
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{slug}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    private function updateUser(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('KnarfUserBundle:User')
                ->findOneBy(['slug' => $request->get('slug')]);

        if (empty($user)) {
            return $this->userNotFound();
        }
        
        $profile = new Profile($user);

//        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
//            $options = ['validation_groups'=>['Default', 'FullUpdate']];
//        } else {
//            $options = []; // Le groupe de validation par défaut de Symfony est Default
//        }

        $form = $this->createForm(ApiEditEmailType::class, $profile);

//        $form->submit($request->request->all(), $clearMissing);
//
//        if ($form->isValid()) {
//            // Si l'utilisateur veut changer son mot de passe
//            if (!empty($user->getPlainPassword())) {
//                $encoder = $this->get('security.password_encoder');
//                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
//                $user->setPassword($encoded);
//            }
//            $em = $this->get('doctrine.orm.entity_manager');
//            $em->merge($user);
//            $em->flush();
//            return $user;
//        }
        if($this->getApiEditEmailFormHandler()->handle($form, $request))
        {
            return $profile;
        }
        else {
            return $form;
        }
    }
    
    /**
     * @ApiDoc(description="Récupère la liste des utilisateurs")
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

        return $users;
    }
    
//    /**
//     * @ApiDoc(description="Récupère un utilisateur")
//     * @Rest\View(serializerGroups={"user"})
//     * @Rest\Get("/users/{slug}")
//     * @param Request $request
//     * @return type
//     */
//    public function getUserAction(Request $request)
//    {
//        $user = $this->getDoctrine()
//                ->getManager()
//                ->getRepository('KnarfUserBundle:User')
//                ->findOneBy(array('slug' => $request->get('slug')));
//        
//        if(empty($user))
//        {
//            return $this->userNotFound();
//        }            
//        
//        return $user;
//    }
    
    /**
     * @ApiDoc(description="Récupère un utilisateur depuis l'id")
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{id}")
     * @param Request $request
     * @return user
     */
    public function getUserIdAction(Request $request)
    {
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('KnarfUserBundle:User')
                ->findOneById(array('id' => $request->get('id')));
        
        if(empty($user))
        {
            return $this->userNotFound();
        }            
        
        return $user;
    }
    
    

    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }    

    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getRegistrationFormHandler()
    {
        return $this->get('app.user_registration.handler');
    }
    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getApiEditEmailFormHandler()
    {
        return $this->get('api.user_edit_email.handler');
    }
    

}
