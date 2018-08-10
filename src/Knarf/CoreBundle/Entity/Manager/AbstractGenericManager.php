<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Entity\Manager;

use Knarf\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use Knarf\CoreBundle\Repository\AbstractGenericRepository;

/**
 * Description of AbstractGenericManager
 *
 * @author franck
 */
abstract class AbstractGenericManager implements GenericManagerInterface
{
    /**
     * @var AbstractGenericRepository $repository
     */
    protected $repository;
    
    /**
     * @inheritdoc
     */
    public function __construct(AbstractGenericRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @inheritdoc
     */
    public function count($enabled = false) 
    {
        return $this->repository->count($enabled);
    }
    
    /**
     * @inheritdoc
     */
    public function remove($entity)
    {
        $this->repository->remove($entity);
    }
    
    /**
     * @inheritdoc
     */
    public function all($result = "object", $maxResults = null, $orderby = '', $dir = 'ASC')
    {
        return $this->repository->findAllByEntity($result, $maxResults, $orderby, $dir);
    }
    
    /**
     * @inheritdoc
     */
    public function find($entity)
    {
        return $this->repository->find($entity);
    }
    
    /**
     * @inheritdoc
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }
    
    /**
     * @inheritdoc
     */
    public function save($entity, $persist = false, $flush = true)
    {
        return $this->repository->save($entity, $persist, $flush);
    }
    
    /**
     * @inheritdoc
     */
    public function isTypeMatch($labelClass)
    {
        return $labelClass === $this->getLabel();
    }
    
    /**
     * @inheritdoc
     */
    abstract public function getLabel();
    
    public function getPagination($request, $page, $route, $maxPerPage, $count = null)
    {
        $pageCount = null === $count ? ceil($this->count() / $maxPerPage) : ceil($count / $maxPerPage);
        return array(
            'page' => $page,
            'route' => $route,
            'pages_count' => $pageCount,
            'route_params' => $request,
        );
    }
}
