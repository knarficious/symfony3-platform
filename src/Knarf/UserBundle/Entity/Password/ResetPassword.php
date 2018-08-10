<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Password;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of ResetPassword
 *
 * @author franck
 */
class ResetPassword 
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    private $password;
    
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
    public function getPassword()
    {
        return $this->password;
    }
}
