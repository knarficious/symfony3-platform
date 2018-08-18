<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Controller;

use Knarf\UserBundle\Form\Type\User\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knarf\UserBundle\Entity\Password\ChangePassword;

/**
 * Description of ChangePasswordController
 *
 * @author franck
 * @Route("/profile")
 */
class ChangePasswordController extends Controller
{
    /**
     * @param Request $request
     * @Route("/modifier_motdepasse", name="change_password")
     * @Method("GET|POST")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request)
    {
        $data = new ChangePassword($this->getUser());
        $form = $this->createForm(ChangePasswordType::class, $data);
        if ($this->getChangePasswordFormHandler()->handle($form, $request)) {
            $this->addFlash('notice', 'The password has been changed successfully.');
            return $this->redirect($this->generateUrl('profile'));
        }
        return $this->render('KnarfUserBundle:Security:change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getChangePasswordFormHandler()
    {
        return $this->get('app.user_change_password.handler');
    }
}
