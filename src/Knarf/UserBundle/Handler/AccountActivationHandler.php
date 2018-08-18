<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Handler;


use Symfony\Component\HttpFoundation\Request;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;

/**
 * Description of AccountActivationHandler
 *
 * @author franck
 */
class AccountActivationHandler 
{
    /*
     * @var UserManagerInterface
     */
    private $userManager;
    
    /*
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager) 
    {
        $this->userManager = $userManager;
    }
    
    /**
     * @param Request $request
     * @param array $options
     * 
     * @return bool
     */
    public function handle(Request $request, array $options = null)
    {
       $token = $request->query->get('token');
       
       if ($this->userManager->getUserByConfirmationToken($token))
       {
           $user = $this->userManager->getUserByConfirmationToken($token);
      
       $this->userManager->clearConfirmationTokenUser($user);
       $this->userManager->setIsEnable($user);
      // $this->userManager->setIpAdress($user, $request->getClientIp());
        
        return true;
       }
       
    }
}
