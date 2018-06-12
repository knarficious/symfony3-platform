<?php

namespace Knarf\UserBundle\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Doctrine\ORM\EntityManager;
use Knarf\UserBundle\Entity\User;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InteractiveLoginListener
 *
 * @author franck
 */
class InteractiveLoginListener {
    
    protected $em;
    protected $request;

    public function __construct(EntityManager $em, RequestStack $request) {

        $this->em = $em;
        $this->request = $request;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {

    /*    $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User) {
            if($this->request->getCurrentRequest()->hasSession()) {
                $user->setLastTimeConnect(new \DateTime('now'));
                $this->em->persist($user);
                $this->em->flush();
            }
        }
    */    
        $this->request->getCurrentRequest()->getSession()->getFlashBag()->add('notice', 'VOUS ETES CONNECTE');
    }
}
