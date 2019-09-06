<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Repository\Interfaces;

/**
 *
 * @author franck
 */
interface AdvertRepositoryInterface 
{
    /**
     * @param $requestVal
     * @return array of adverts
     */
    public function getResultFilterCount($requestVal);
    
    public function getResultFilterPaginated($requestVal, $limit = 20, $offset = 0);
    
    public function getQueryResultFilter($requestVal);
}
