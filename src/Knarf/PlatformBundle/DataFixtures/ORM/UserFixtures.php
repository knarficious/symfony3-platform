<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Knarf\UserBundle\Entity\App_User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of UserFixture
 *
 * @author franck
 */
class UserFixtures extends Fixture
{    
    private $encoder;
    const ADMIN_USER_REFERENCE = 'user1';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager) 
    {
        $user1 = new App_User();
        $user1->setUsername('Admin');
        $user1->setPassword($this->encoder->encodePassword($user1, 'admin'));
        //$user1->setSlug('knarf');
        $user1->setEmail('admin@mail.com');
        $user1->setRoles(array('ROLE_ADMIN'));
        $user1->setIsActive(true);
        $user1->setCreatedAt(new \DateTime('now'));
        $user1->setUpdatedAt(new \DateTime('now'));
        $manager->persist($user1);
        
        $user2 = new App_User();        
        $user2->setUsername('User 2');
        $user2->setPassword($this->encoder->encodePassword($user2, 'password'));
        //$user2->setSlug('franck');
        $user2->setEmail('user2@mail.com');
        $user2->setRoles(array('ROLE_ADMIN'));
        $user2->setIsActive(true);
        $user2->setCreatedAt(new \DateTime('now'));
        $user2->setUpdatedAt(new \DateTime('now'));
        $manager->persist($user2);
        
        $user3 = new App_User();        
        $user3->setUsername('User 3');
        $user3->setPassword($this->encoder->encodePassword($user3, 'password'));
        //$user3->setSlug('bob');
        $user3->setEmail('user3@mail.com');
        $user3->setRoles(array('ROLE_USER'));
        $user3->setIsActive(true);
        $user3->setCreatedAt(new \DateTime('now'));
        $user3->setUpdatedAt(new \DateTime('now'));
        $manager->persist($user3);
        
        
        $manager->flush();
        
        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        $this->addReference(self::ADMIN_USER_REFERENCE, $user1);
        $this->addReference('user2', $user2);
        $this->addReference('user3', $user3);
    }


}
