<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Knarf\UserBundle\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Knarf\CoreBundle\Validator\Constraints as CoreAssert;
use Knarf\UserBundle\Validator\Constraints as KnarfAssert;

/**
 * Description of Profil
 *
 * @author franck
 * 
 */
class Profile 
{
    /**
     * @var UserInterface
     */
    private $user;
    
    /**
     * @Assert\NotBlank
     * @Assert\Email()
     * @CoreAssert\UniqueAttribute(
     *      repository="Knarf\UserBundle\Entity\App_User",
     *      property="email"
     * )
     * @KnarfAssert\EmailBlackList()
     */
    private $email;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }    

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email= $email;
    }    
}
