<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of UserDataEvent
 *
 * @author franck
 */
class UserDataEvent extends Event
{
    /**
     * @var UserInterface
     */
    protected $user;
    
    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
