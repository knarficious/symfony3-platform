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
use Twig_Environment;

/**
 * Description of SendRequestPasswordMailListener
 *
 * @author franck
 */
class SendRequestPasswordMailListener 
{
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
     * @var TokenGeneratorInterface $tokenGenerator
     */
    protected $tokenGenerator;
    
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
    
    /**
     * @param MailerServiceInterface $mailerService
     * @param Twig_Environment $templating
     * @param $template
     * @param $from
     */
    public function __construct(MailerServiceInterface $mailerService, Twig_Environment $templating, RouterInterface $router,
                                TokenGeneratorInterface $tokenGenerator, UserManagerInterface $userManager, $template,
                                $from)
    {
        $this->mailerService = $mailerService;
        $this->templating = $templating;
        $this->router = $router;
        $this->tokenGenerator = $tokenGenerator;
        $this->userManager = $userManager;
        $this->template = $template;
        $this->from = $from;
    }
    /**
     * @param UserDataEvent $event
     */
    public function onRequestedPassword(UserDataEvent $event)
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
                    'request_link' => $this->router->generate('reset_password',
                        ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
                ])
        );
    }
}
