<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Services;

use Knarf\PlatformBundle\Services\Interfaces\ManagerServiceInterface;

/**
 * Description of ManagerService
 *
 * @author franck
 */
class ManagerService implements ManagerServiceInterface
{
    /**
     * @var ManagerContainerService
     */
    private $managerContainerService;
    
    public function getManagerClass($managerClassLabel) 
    {
        foreach ($this->managerContainerService->getManagers() as $manager)
        {
            if ($manager->isTypeMatch($managerClassLabel)) {
                return $manager;
            }
        }
        throw new \Exception(sprintf('None manager service found for class %s', $managerClassLabel));
    }

    public function setManagerContainerService(ManagerContainerService $managerContainerService) 
    {
        $this->managerContainerService = $managerContainerService;
    }

}
