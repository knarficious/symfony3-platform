<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Manager\Interfaces;

use Knarf\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use Knarf\UserBundle\Entity\App_User;

/**
 *
 * @author franck
 */
interface UserManagerInterface extends GenericManagerInterface 
{
   /**
     * @param App_User $user
     *
     * @return void
     */
    public function createUser(App_User $user, $adresseIp);
    
    /**
     * @param App_User $admin
     * 
     * @return void
     */
    public function createAdmin(App_User $admin, $adresseIp);
    
    /**
     * 
     * @param App_User $user
     * 
     * @return void
     */
    public function deleteUser(App_User $user);
    
    /**
     * @param App_User $user
     * @param $newPassword
     * @return mixed
     */
    public function updateCredentials(App_User $user, $newPassword);
    
    /**
     * @param App_User $user
     * @param $plainPassword
     * @return mixed
     */
    public function isPasswordValid(App_User $user, $plainPassword);
    
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
     * @param App_User $user
     * @param $token
     * @return mixed
     */
    public function updateConfirmationTokenUser(App_User $user, $token);
    
    /**
     * @param $token
     * @return mixed
     */
    public function getUserByConfirmationToken($token);
    
    /**
     * @param App_User $user
     * @return mixed
     */
    public function clearConfirmationTokenUser(App_User $user);
    
    /**
     * @param App_User $user
     * @param \Datetime $lastConnexion
     */
    public function setLastConnexion(App_User $user, \Datetime $lastConnexion);
    
    /**
     * @param App_User $user
     * @return bool
     */
    public function setIsEnable(App_User $user);
    
    /**
     * @param App_User $user
     * @param $email
     * @return mixed
     */
    public function updateUserEmail(App_User $user, $email);
    
    /**
     * @param App_User $user
     * @param $avatar
     * @return mixed
     */
    public function updateUserAvatar(App_User $user, $avatar);
    
    /**
     * @param App_User $user
     * @param $adresseIp
     */
    public function setIp(App_User $user, $adresseIp);
    
    /**
     *
     */
    public function getNeverConnect();
    
    public function getOldConnect();
    
    public function getNotActive();


}


