<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

/**
 * Description of AuthenticationSuccessHandler
 *
 * @author franck
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
        private $logger;
    
    public function __construct(LoggerInterface $logger, HttpUtils $httpUtils, array $options)
    {
        parent::__construct($httpUtils, $options);
        
        $this->logger = $logger;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();
        $this->logger->info("User " . $user->getId() . " has been logged", ['user' => $user]);
        $response = parent::onAuthenticationSuccess($request, $token);
        $response->headers->setCookie(new Cookie('success_connection', $token->getUsername(), 0));
        $request->getSession()->getFlashBag()->add('notice', 'Bienvenue '.$token->getUsername()  .', VOUS ETES CONNECTE');
        
        
        return $response;
    }
}
