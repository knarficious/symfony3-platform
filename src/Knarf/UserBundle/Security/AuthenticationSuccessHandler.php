<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;

/**
 * Description of AuthenticationSuccessHandler
 *
 * @author franck
 */
class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var LoggerInterface $logger
     */    
    private $logger;
    
    /**
     * @var UserManagerInterface $userManager
     */
    private $userManager;
    
    /**
     *
     * @var HttpUtils $httpUtils 
     */
    private $httpUtils;
        
    
    /** 
     * @param LoggerInterface $logger
     * @param HttpUtils $httpUtils
     * @param UserManagerInterface $userManager
     */
    public function __construct(LoggerInterface $logger, HttpUtils $httpUtils,  UserManagerInterface $userManager)
    {
       $this->logger = $logger;
       $this->httpUtils = $httpUtils;
       $this->userManager = $userManager;
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $session = $request->getSession();
                
        if ($session->has('_security.main.target_path'))
        {
            if ($session->get('_security.main.target_path') !== null && $session->get('_security.main.target_path') !== '')
            {
                $response = new RedirectResponse($session->get('_security.main.target_path'));
            } 
            else
            {
                $response = new RedirectResponse($request->getBasePath() . '/');
            }
        }
        else
        {
            $response = new RedirectResponse($request->headers->get('referer'));
        }
        
        $user = $token->getUser();
        $this->logger->info("User " . $user->getId() . " has been logged", ['user' => $user]);
       // $response = parent::onAuthenticationSuccess($request, $token);
        $response->headers->setCookie(new Cookie('success_connection', $token->getUsername(), 0));
        $request->getSession()->getFlashBag()->add('success', 'Bienvenue '.$token->getUsername()  .', VOUS ETES CONNECTE');
        $this->userManager->setLastConnexion($user, new \DateTime('now'));
        $this->userManager->save($user, false, true);
        
        return $response;
    }
}
