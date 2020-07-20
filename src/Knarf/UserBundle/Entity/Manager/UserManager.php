<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Manager;

use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Knarf\CoreBundle\Entity\Manager\AbstractGenericManager;
use Knarf\UserBundle\KnarfUserEvents;
use Knarf\UserBundle\Entity\App_User;
use Knarf\UserBundle\Event\UserDataEvent;
use Knarf\UserBundle\Repository\UserRepository;

/**
 * Description of UserManager
 *
 * @author franck
 */
class UserManager extends AbstractGenericManager implements UserManagerInterface
{
    /**
     * @var EncoderFactoryInterface $encoderFactory
     */
    protected $encoderFactory;
    
    /**
     * @var EventDispatcherInterface $dispatcher
     */
    protected $dispatcher;
    
    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    protected $encoder;
    
    /**
     *
     * @var UserRepository $repository
     */
    protected $repository;
    
    /**
     * @param EncoderFactoryInterface       $encoderFactory
     * @param EventDispatcherInterface      $dispatcher
     * @param UserPasswordEncoderInterface  $encoder
     * @param UserRepository                $userRepository
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        EventDispatcherInterface $dispatcher,
        UserPasswordEncoderInterface $encoder,
        UserRepository $userRepository
    )
    {
        $this->encoderFactory = $encoderFactory;
        $this->dispatcher = $dispatcher;
        $this->encoder = $encoder;
        $this->repository = $userRepository;
    }
    
    public function clearConfirmationTokenUser(App_User $user) 
    {
        $user->setConfirmationToken(null);
        $user->setIsAlreadyRequested(false);
    }

    public function createUser(App_User $user, $adresseIp) 
    {
        $user->setCgvRead(false);
        $user->setRoles(['ROLE_USER']);
        $user->encodePassword($this->encoderFactory->getEncoder($user));
        $user->setAdresseIp($adresseIp);
        $this->save($user, true, true);
        $this->dispatcher->dispatch(
            KnarfUserEvents::NEW_ACCOUNT_CREATED, new UserDataEvent($user)
        );
    }
    
    public function getUserByConfirmationToken($token) 
    {
        return $this->repository->getUserByToken($token);
    }

    public function getUserByIdentifier($identifier) 
    {
        return $this->repository->getUserByIdentifier($identifier);
    }

    public function isPasswordValid(App_User $user, $plainPassword)
    {
        return $this->encoder->isPasswordValid($user, $plainPassword);
    }

    public function sendRequestPassword($user) 
    {
        $this->dispatcher->dispatch(
            KnarfUserEvents::NEW_PASSWORD_REQUESTED, new UserDataEvent($user)
        );
    }

    public function setLastConnexion(App_User $user, \Datetime $lastConnexion) 
    {
        $user->setLastTimeConnect($lastConnexion);
        
    }

    public function updateConfirmationTokenUser(App_User $user, $token)
    {
        $user->setConfirmationToken($token);
        $user->setIsAlreadyRequested(true);
        $this->save($user, false, true);
    }

    public function updateCredentials(App_User $user, $newPassword) 
    {
        $user->setPlainPassword($newPassword);
        $user->encodePassword($this->encoderFactory->getEncoder($user));
        $this->save($user, false, true);
    }

    public function setIsEnable(App_User $user)
    {
        $user->setIsActive(true);
        $this->save($user, true, true);
    }

    public function getLabel()
    {
        return 'UserManager';
    }
    
    public function updateUserEmail(App_User $user, $email) {
        $user->setEmail($email);
        $this->save($user, false, true);
    }

    public function updateUserAvatar(App_User $user, $avatar) {
        $user->setUpdatedAt(new \DateTime('now'));
        $user->setAvatar($avatar);
        $this->save($user, false, true);        
    }

    public function setIp(App_User $user, $adresseIp) {
        $user->setAdresseIp($adresseIp);
    }

    public function createAdmin(App_User $admin, $adresseIp)
    {
        $admin->setCgvRead(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->encodePassword($this->encoderFactory->getEncoder($admin));
        $admin->setAdresseIp($adresseIp);
        $this->save($admin, true, true);
        $this->dispatcher->dispatch(
            KnarfUserEvents::NEW_ACCOUNT_CREATED, new UserDataEvent($admin)
        );
    }
    
    public function getNeverConnect()
    {
        return $this->repository->getAllNeverConnectedUsers();      

    }
    
    public function getOldConnect() 
    {
        $now = new \DateTime();
        return $this->repository->getAllOldConnectedUsers($now);;
    }

    public function getNotActive() 
    {
        return $this->repository->getNotActiveUsers();
    }

}
