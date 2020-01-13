<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Repository\Interfaces;

use Knarf\UserBundle\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;

/**
 *
 * @author franck
 */
interface UserRepositoryInterface 
{
    /**
     * @param QueryBuilder $qb
     * @param $identifier
     * @return UserRepository
     */
    public function getUserByIdentifierQueryBuilder(QueryBuilder $qb, $identifier);
    
    /**
     * @param $identifier
     * @return mixed
     */
    public function getUserByIdentifier($identifier);
    
    /**
     * @param $token
     * @return UserRepository
     */
    public function getUserByToken($token);
    
    /**
     * @return UserRepository 
     */
    public function getAllNeverConnectedUsers();
    
    /**
     * @param DateTime $date
     * @return UserRepository
     */
    public function getAllOldConnectedUsers(\DateTime $date);
}

    
