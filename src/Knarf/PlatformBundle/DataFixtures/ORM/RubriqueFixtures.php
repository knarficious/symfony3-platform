<?php
// src/Knarf/PlatformBundle/DataFixtures/ORM/LoadUser.php

namespace Knarf\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Knarf\PlatformBundle\Entity\Rubrique;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RubriqueFixtures
 *
 * @author franck
 */
class RubriqueFixtures extends AbstractFixture
{
    const RUBRIQUE_REFERENCE_1 = 'rubrique1';
    const RUBRIQUE_REFERENCE_2 = 'rubrique2';
    const RUBRIQUE_REFERENCE_3 = 'rubrique3';
    const RUBRiQUE_REFERENCE_4 = 'rubrique4';
    
    public function load(ObjectManager $manager)
   {
        $rubrique1 = new Rubrique();
        $rubrique1->setIntitule('Rubrique 1');
        $rubrique1->setUpdateAt(new \DateTime('now'));
        $manager->persist($rubrique1); 
        
        $rubrique2 = new Rubrique();
        $rubrique2->setIntitule('Rubrique 2');
        $rubrique2->setUpdateAt(new \DateTime('now'));
        $manager->persist($rubrique2);
        
        $rubrique3 = new Rubrique();
        $rubrique3->setIntitule('Rubrique 3');
        $rubrique3->setUpdateAt(new \DateTime('now'));
        $manager->persist($rubrique3);
        
        $rubrique4 = new Rubrique();
        $rubrique4->setIntitule('Rubrique 4');
        $rubrique4->setUpdateAt(new \DateTime('now'));
        $manager->persist($rubrique4);
    
        $manager->flush();
        
        $this->addReference(self::RUBRIQUE_REFERENCE_1, $rubrique1);
        $this->addReference(self::RUBRIQUE_REFERENCE_2, $rubrique2);
        $this->addReference(self::RUBRIQUE_REFERENCE_3, $rubrique3);
        $this->addReference(self::RUBRiQUE_REFERENCE_4, $rubrique4);
    }


}
