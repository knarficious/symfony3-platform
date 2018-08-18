<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Form\Handler\User;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Knarf\CoreBundle\Form\Handler\FormHandlerInterface;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;
/**
 * Description of ResetPasswordFormHandler
 *
 * @author franck
 */
class ResetPasswordFormHandler implements FormHandlerInterface
{
    /**
     *
     * @var UserManagerInterface
     */
    private $handler;
    
    /**
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->handler = $userManager;
    }
    
    /**
     * @param FormInterface $form
     * @param Request       $request
     * @param array         $options
     *
     * @return bool
     */
    public function handle(FormInterface $form, Request $request, array $options = null) 
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return false;
        }
        
        $token = $request->query->get('token');
        $user = $this->handler->getUserByConfirmationToken($token);
        $this->handler->clearConfirmationTokenUser($user);
        $this->handler->updateCredentials($user, $form->getData()->getPassword());
        
        return true;
    }

}
