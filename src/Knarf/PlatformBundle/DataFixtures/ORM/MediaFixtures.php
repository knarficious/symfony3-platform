<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Knarf\PlatformBundle\Entity\Media;

/**
 * Description of MediaFixtures
 *
 * @author franck
 */
class MediaFixtures extends AbstractFixture
{
    const MEDIA_REF_1 = 'media1';
    const MEDIA_REF_2 = 'media2';
    const MEDIA_REF_3 = 'media3';
    const MEDIA_REF_4 = 'media4';
    
    public function load(ObjectManager $manager) 
    {
        $media1 = new Media();
        $media1->setNomMedia('5e5aa546a6336_20170624_153357.jpg');
        $media1->setDate(new \DateTime('now'));
        $manager->persist($media1);
        
        $media2 = new Media();
        $media2->setNomMedia('5e5ab2a3ec61c_Cumulus_Clouds_over_Yellow_Prairie2.jpg');
        $media2->setDate(new \DateTime('now'));
        $manager->persist($media2);
        
        $media3 = new Media();
        $media3->setNomMedia('ND2ROUEN_01.jpg');
        $media3->setDate(new \DateTime('now'));
        $manager->persist($media3);
        
        $media4 = new Media();
        $media4->setNomMedia('goldorak_rdpoint.jpg');
        $media4->setDate(new \DateTime('now'));
        $manager->persist($media4);
        
        $manager->flush();
        
        $this->addReference(self::MEDIA_REF_1, $media1);
        $this->addReference(self::MEDIA_REF_2, $media2);
        $this->addReference(self::MEDIA_REF_3, $media3);
        $this->addReference(self::MEDIA_REF_4, $media4);
    }

}
