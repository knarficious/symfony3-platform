<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Entity\Manager\Interfaces;

/**
 *
 * @author franck
 */
interface GenericManagerInterface
{
    /**
     * Count all fields existed from the given entity
     *
     * @param boolean $enabled [0, 1]
     *
     * @return int the count of all fields.
     * @access public
     */
    public function count($enabled = false);
    
    /**
     * @param $entity
     */
    public function remove($entity);
    
    /**
     * @param string $result
     * @param null $maxResults
     * @param string $orderby
     * @param string $dir
     * @return array
     */
    public function all($result = "object", $maxResults = null, $orderby = '', $dir = 'ASC');
    
    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
    
    /**
     * @param $entity
     * @return null|object
     */
    public function find($entity);
    
    /**
     * @param $entity
     * @param bool $persist
     * @param bool $flush
     * @return mixed
     */
    public function save($entity, $persist = false, $flush = true);
    
    /**
     * @param $labelClass
     * @return GenericManagerInterface
     */
    public function isTypeMatch($labelClass);
    
    /**
     * @return string LabelClass
     */
    public function getLabel();
}
