<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Knarf\UserBundle\Form\Type\User\RegistrationType;
use Knarf\UserBundle\Entity\Registration\Registration;

/**
 * Description of RegisterController
 *
 * @author franck
 */
class RegisterController extends Controller
{
    /**
     * @param Request $request
     * @Route("/register", name="register")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('profile');
        }
        
        $form = $this->createForm(RegistrationType::class, new Registration());
        
        if ($this->getRegistrationFormHandler()->handle($form, $request))
        {
            $email = $form->getData()->getEmail();
            $this->addFlash('email', $email);
            return $this->redirectToRoute('info_activation');
        }
        
        return $this->render(
            'KnarfUserBundle:Security:register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * @Route("/register-admin", name="register_admin")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return type
     */
    public function registerAsAdminAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('profile');
        }
        
        $form = $this->createForm(RegistrationType::class, new Registration());
        
        if ($this->getRegistrationAdminFormHandler()->handle($form, $request))
        {
            $email = $form->getData()->getEmail();
            $this->addFlash('email', $email);
            return $this->redirectToRoute('info_activation');
        }
        
        return $this->render(
            'KnarfUserBundle:Security:register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * @Route("/info-activation", name="info_activation")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response

     */
    public function infoActivateAction()
    {

        // Si le visiteur est déjà identifié, on le redirige
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            {
                return $this->redirectToRoute('profile');
            }        
        
        return $this->render(
                'KnarfUserBundle:Security:info_activation.html.twig');
    }
    
    /*
     * @Route("/activate", name="registration")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Request $request)
    {
        $token = $request->query->get("token");
        //on teste si le jeton existe en BDD
        if(!$this->getDoctrine()->getManager()->getRepository('KnarfUserBundle:User')->getUserByToken($token))
                {
                   $this->addFlash('notice', 'Le lien a expiré ou votre compte est déjà activé');
                    return $this->redirectToRoute('knarf_platform_home');
                }
        
        if ($this->getAccountActivationHandler()->handle($request))
        {
           // $this->addFlash('notice', 'Votre compte est activé : Vous pouvez vous connecter');
            return $this->redirect($this->generateUrl('security_login_form'));
        }
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
    protected function getRegistrationAdminFormHandler()
    {
        return $this->get('app.admin_registration.handler');
    }
    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getAccountActivationHandler()
    {
        return $this->get('app.account_activation.handler');
    }
    
    
}
