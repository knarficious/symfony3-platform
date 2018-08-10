<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knarf\UserBundle\Form\Type\User\ResetPasswordType;
use Knarf\UserBundle\Entity\Password\ResetPassword;

/**
 * Description of ResetPasswordController
 *
 * @author franck
 *
 */
class ResetPasswordController extends Controller
{
    
/** 
 * @Route("/reset-password", name="reset_password")
 * @Method("GET|POST")
 * @param Request $request
 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
 */    
public function resetPasswordAction( Request $request)
{
    if ($this->isGranted('IS_AUTHENTICATED_FULLY')) 
    {
    return $this->redirect($this->generateUrl('profile'));

    }
    
    $form = $this->createForm(ResetPasswordType::class, new ResetPassword() );
    
    if($this->getResetPasswordFormHandler()->handle($form, $request))
    {
        $this->addFlash('notice', 'Votre mot de passe a été modifié avec succès');
        return $this->redirect($this->generateUrl('security_login_form'));
    }
    
    return $this->render('KnarfUserBundle:Security:reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    
}

protected function getResetPasswordFormHandler()
{
    return $this->get('app.user_reset_password.handler');
}


    
}
