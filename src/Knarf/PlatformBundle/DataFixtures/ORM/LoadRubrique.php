<?php
// src/Knarf/PlatformBundle/DataFixtures/ORM/LoadUser.php

namespace Knarf\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Knarf\PlatformBundle\Entity\Rubrique;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoadAdvert
 *
 * @author franck
 */
class LoadRubrique implements FixtureInterface
{
    public function load(ObjectManager $manager)
   {
            
        $rubrique1 = new Rubrique();
        $rubrique1->setIntitule('Sciences');
 
        
        $rubrique2 = new Rubrique();
        $rubrique2->setIntitule('Musique');

        
        $rubrique3 = new Rubrique();
        $rubrique3->setIntitule('Géopolitique');

        
        $manager->persist($rubrique1);
        $manager->persist($rubrique2);
        $manager->persist($rubrique3);

        $manager->flush();
    }
}
