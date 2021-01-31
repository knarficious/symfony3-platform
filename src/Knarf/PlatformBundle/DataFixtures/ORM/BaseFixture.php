<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\DataFixtures\ORM;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of BaseFixture
 *
 * @author franck
 */
abstract class BaseFixture extends Fixture 
{
    /**
     *
     * @var ObjectManager 
     */
    private $manager;
    
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadData($manager);
    }
    
    abstract protected function loadData(ObjectManager $manager);
    
    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }   
    

}
