<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Interfaces;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as SecurityUserInterface;

/**
 *
 * @author franck
 */
interface UserInterface extends SecurityUserInterface 
{
    public function encodePassword(PasswordEncoderInterface $encoder);
    
    public function getPlainPassword();
    public function setPlainPassword($plainPassword);
    
    public function getConfirmationToken();
    public function setConfirmationToken($confirmationToken);
    
    public function getIsAlreadyRequested();
    public function setIsAlreadyRequested($isAlreadyRequested);
    
    public function setRoles(array $roles);
    
    public function isCgvRead();
    public function setCgvRead($cgvRead);
    
    public function getIsActive();
    public function setIsActive($isActive);
    
    public function getEmail();
    public function setEmail($email);
    
    public function getLastTimeConnect();
    public function setLastTimeConnect(\DateTime $lastTimeConnect);
    
    public function getNomMedia();
    public function setNomMedia($nomMedia);
    
    public function getMediaFile();
    public function setMediaFile($mediaFile);
    
    public function setUpdatedAt();
    
}
