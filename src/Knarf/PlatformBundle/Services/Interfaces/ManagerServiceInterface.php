<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Services\Interfaces;

use Knarf\PlatformBundle\Services\ManagerContainerService;

/**
 *
 * @author franck
 */
interface ManagerServiceInterface 
{
    /**
     * @param $managerClassLabel
     * @return ManagerServiceInterface $managerService
     * @throws \Exception
     */
    public function getManagerClass($managerClassLabel);
    
    /**
     * @param ManagerContainerService $managerContainerService
     */
    public function setManagerContainerService(ManagerContainerService $managerContainerService);
}
