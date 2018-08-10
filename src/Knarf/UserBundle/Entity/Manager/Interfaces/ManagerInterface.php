<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\Manager\Interfaces;

/**
 *
 * @author franck
 */
interface ManagerInterface  
{
    /**
     * @param $labelClass
     * @return ManagerInterface
     */
    public function isTypeMatch($labelClass);
    
    /**
     * @return string LabelClass
     */
    public function getLabel();
}
