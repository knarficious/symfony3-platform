<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Manager\Interfaces;

use Knarf\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use Knarf\UserBundle\Entity\Interfaces\UserInterface;

/**
 *
 * @author franck
 */
interface UserManagerInterface extends GenericManagerInterface 
{
   /**
     * @param UserInterface $user
     *
     * @return void
     */
    public function createUser(UserInterface $user);
    
    /**
     * @param UserInterface $user
     * @param $newPassword
     * @return mixed
     */
    public function updateCredentials(UserInterface $user, $newPassword);
    
    /**
     * @param UserInterface $user
     * @param $plainPassword
     * @return mixed
     */
    public function isPasswordValid(UserInterface $user, $plainPassword);
    
    /**
     * @param $identifier
     * @return mixed
     */
    public function getUserByIdentifier($identifier);
    
    /**
     * @param $user
     * @return mixed
     */
    public function sendRequestPassword($user);
    
    /**
     * @param UserInterface $user
     * @param $token
     * @return mixed
     */
    public function updateConfirmationTokenUser(UserInterface $user, $token);
    
    /**
     * @param $token
     * @return mixed
     */
    public function getUserByConfirmationToken($token);
    
    /**
     * @param UserInterface $user
     * @return mixed
     */
    public function clearConfirmationTokenUser(UserInterface $user);
    
    /**
     * @param UserInterface $user
     * @param \Datetime $lastConnexion
     */
    public function setLastConnexion(UserInterface $user, \Datetime $lastConnexion);
    
    /**
     * @param UserInterface $user
     * @return bool
     */
    public function setIsEnable(UserInterface $user);
    
    /**
     * @param UserInterface $user
     * @param $email
     * @return mixed
     */
    public function updateUserEmail(UserInterface $user, $email);
    
    /**
     * @param UserInterface $user
     * @param $nomMedia
     * @param $mediaFile
     * @return mixed
     */
    public function updateUserImage(UserInterface $user, $nomMedia, $mediaFile);
    
    /**
     * @param UserInterface $user
     * @param $adresseIp
     */
    public function setIp(UserInterface $user, $adresseIp);

}
