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
 * Description of RequestPasswordFormHandler
 *
 * @author franck
 */
class RequestPasswordFormHandler implements FormHandlerInterface
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
        $this->handler->sendRequestPassword($form->getData()->getUser());
        return true;
    }

}
