<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\EventListener;

use Knarf\UserBundle\Event\UserDataEvent;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;
use Knarf\CoreBundle\Services\Interfaces\MailerServiceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Psr\Log\LoggerInterface;


/**
 * Description of SendConfirmationMailListener
 *
 * @author franck
 */
class SendConfirmationMailListener 
{
    /**
     * @var LoggerInterface $logger
     */    
    protected $logger;
    
    /**
     * @var MailerServiceInterface $mailerService
     */
    protected $mailerService;
    
    /**
     * @var \Twig_Environment
     */
    protected $templating;
    
    /**
     * @var string
     */
    protected $template;
    
    /**
     * @var string $from
     */
    protected $from;  

    /**
     * @var RouterInterface $router
     */
    protected $router;
    
    /**
     * @var TokenGeneratorInterface $tokenGenerator
     */
    protected $tokenGenerator;
    
    /**
     * @var UserManagerInterface $userManager
     */
    protected $userManager;
    
    /**
     * @param MailerServiceInterface $mailerService
     * @param \Twig_Environment $templating
     * @param string $template
     * @param string $from
     */
    public function __construct(
            LoggerInterface $logger,
            MailerServiceInterface $mailerService, 
            \Twig_Environment $templating,
            RouterInterface $router,
            TokenGeneratorInterface $tokenGenerator,
            UserManagerInterface $userManager,
            $template, 
            $from)
    {
        $this->logger = $logger;
        $this->mailerService = $mailerService;
        $this->templating = $templating;
        $this->template = $template;
        $this->router = $router;
        $this->tokenGenerator = $tokenGenerator;
        $this->userManager = $userManager;
        $this->from = $from;
    }
    
    /**
     * @param UserDataEvent $event
     */
    public function onNewAccountCreated(UserDataEvent $event)
    {
        $user = $event->getUser();
        $token = $this->tokenGenerator->generateToken();
        $this->userManager->updateConfirmationTokenUser($user, $token);
        $this->mailerService->sendMail(
            $this->from,
            $user->getEmail(),
            $this->templating->loadTemplate($this->template)->renderBlock('subject', []),
            $this->templating->loadTemplate($this->template)->renderBlock('body',
                [
                    'username' => $user->getUsername(),
                    'request_link' => $this->router->generate('registration',
                        ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
                ])
        );
        $this->logger->info('User ' . $user->getUsername(). ' CREATED A NEW ACCOUNT', ['user' => $user]);
    }
}
