<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Registration;

use Symfony\Component\Validator\Constraints as Assert;
use Knarf\CoreBundle\Validator\Constraints as CoreAssert;
use Knarf\UserBundle\Validator\Constraints as KnarfAssert;
use Knarf\UserBundle\Entity\App_User;

/**
 * Description of Registration
 *
 * @author franck
 */
class Registration 
{
    /**
     * @Assert\NotBlank()
     * @CoreAssert\UniqueAttribute(
     *      repository="Knarf\UserBundle\Entity\App_User",
     *      property="username"
     * )
     */
    private $username;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, minMessage = "registration.password.minlength")
     */
    private $password;
    
    /**
     * @Assert\NotBlank(message = "registration.email.notblank")
     * @Assert\Email()
     * @CoreAssert\UniqueAttribute(
     *      repository="Knarf\UserBundle\Entity\App_User",
     *      property="email"
     * )
     * @KnarfAssert\EmailBlackList()
     */
    private $email;
    
    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * @return App_User
     */
    public function getUser()
    {
        $user = new App_User();
        $user->setUsername($this->username);
        $user->setEmail($this->email);
        $user->setPlainPassword($this->password);
        $user->setCreatedAt(new \DateTime);
        $user->setUpdatedAt(new \DateTime);
        $user->setIsActive(false);
        return $user;
    }
}
