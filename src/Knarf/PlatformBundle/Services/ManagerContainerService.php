<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Services;

use Knarf\PlatformBundle\Entity\Manager\Interfaces\ManagerInterface;

/**
 * Description of ManagerContainerInterface
 *
 * @author franck
 */
class ManagerContainerService 
{
    private $managers;
    
    public function __construct()
    {
        $this->managers = array();
    }
    
    public function addManager(ManagerInterface $manager)
    {
        array_push($this->managers, $manager);
    }
    
    public function getManagers()
    {
        return $this->managers;
    }
}
