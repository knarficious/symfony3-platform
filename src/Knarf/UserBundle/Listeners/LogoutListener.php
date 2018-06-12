<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Listeners;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Knarf\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Description of LogoutListener
 *
 * @author franck
 */
class LogoutListener implements LogoutHandlerInterface{
    
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    
    /**
     * @{inheritDoc}
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $user = $token->getUser();
        
        if($user instanceof User)
        {
            $user->setLastTimeConnect(new \DateTime('now'));
            $this->em->persist($user);
            $this->em->flush();
        }
        
        $request->getSession()->getFlashBag()->add('notice', 'Vous êtes désormais déconnecté');
        
    }

}
