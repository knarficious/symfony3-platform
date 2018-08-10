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
use Knarf\UserBundle\Form\Type\User\RequestPasswordType;
use Knarf\UserBundle\Entity\Password\RequestPassword;

/**
 * Description of RequestPasswordController
 *
 * @author franck
 * 
 * @Route("/")
 */
class RequestPasswordController extends Controller
{
        /**
     * @param Request $request
     * @Route("/request-password", name="request_password")
     * @Method("GET|POST")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function requestPasswordAction(Request $request)
    {
        $form = $this->createForm(RequestPasswordType::class, new RequestPassword());
        if ($this->getRequestPasswordFormHandler()->handle($form, $request)) {
            $this->addFlash('notice', 'A mail has been sent to your mailbox to reset your password.');
            return $this->redirect($this->generateUrl('knarf_platform_home'));
        }
        return $this->render('KnarfUserBundle:Security:request-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getRequestPasswordFormHandler()
    {
        return $this->get('app.user_request_password.handler');
    }
}
