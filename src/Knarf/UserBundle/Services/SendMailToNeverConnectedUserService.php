<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Services;


use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;
use Knarf\CoreBundle\Services\Interfaces\MailerServiceInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;
use Twig_Environment;

/**
 * Description of SendMailToNeverConnectedUserService
 *
 * @author franck
 */
class SendMailToNeverConnectedUserService 
{
    /**
     *
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
     * @var RouterInterface $router
     */
    protected $router;

    
    /**
     * @var UserManagerInterface $userManager
     */
    protected $userManager;
    
    /**
     * @var array
     */
    protected $template;
    
    /**
     * @var string $from
     */
    protected $from;
    
    public function __construct(
            LoggerInterface $logger,
            MailerServiceInterface $mailerService,
            Twig_Environment $templating, 
            RouterInterface $router, 
            UserManagerInterface $userManager, 
            $template,
            $from) 
    {
        $this->logger = $logger;
        $this->mailerService = $mailerService;
        $this->router = $router;
        $this->template = $template;
        $this->templating = $templating;
        $this->userManager = $userManager;
        $this->from = $from;
        
    }
    
    public function sendMailToUsers() 
    {
        $users = $this->userManager->getLastTimeConnect();
        
        foreach ($users as $user) 
        {
            $this->mailerService->sendMail(
            $this->from,
            $user->getEmail(),
            $this->templating->loadTemplate($this->template)->renderBlock('subject', []),
            $this->templating->loadTemplate($this->template)->renderBlock('body',
                [
                    'username' => $user->getUsername(),
                    'dateCreation' => $user->getCreatedAt()
            
                ])
            );          
            
        }
        
        $this->logger->info('User ' . $user->getUsername(). ' RECEIVED AN EMAIL FOR LOGGING', ['user' => $user]);
    }
}
