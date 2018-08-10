<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Password;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of RequestPassword
 *
 * @author franck
 */
class RequestPassword 
{
    /**
     *
     * @var UserInterface
     */
    private $user;
    
    /**
     * @Assert\NotBlank
     */
    private $identifier;
    
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
    
    function getUser()
    {
        return $this->user;
    }
    
    function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
}
