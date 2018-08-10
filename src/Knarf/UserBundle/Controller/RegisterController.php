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
     * @Route("/register")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
        return $this->redirectToRoute('profile');
    }
        $form = $this->createForm(RegistrationType::class, new Registration());
        
        if ($this->getRegistrationFormHandler()->handle($form, $request))
        {
            
            $this->addFlash('notice', 'Votre compte est créé : Un email vous a été expédié');
            return $this->redirectToRoute('security_login_form');
        }
        
        return $this->render(
            'KnarfUserBundle:Security:register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /*
     * @Route("/activate"
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Request $request)
    {
        if ($this->getAccountActivationHandler()->handle($request))
        {
            $this->addFlash('notice', 'Votre compte est activé : Vous pouvez vous connecter');
            return $this->redirectToRoute('security_login_form');
        }
    }
    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getRegistrationFormHandler()
    {
        return $this->get('app.user_registration.handler');
    }
    
    
    protected function getAccountActivationHandler()
    {
        return $this->get('app.account_activation.handler');
    }
}
