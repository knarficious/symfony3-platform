<?php

namespace Knarf\UserBundle\Repository;

use Knarf\UserBundle\Repository\Interfaces\UserRepositoryInterface;
use Knarf\CoreBundle\Repository\AbstractGenericRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\QueryBuilder;


class UserRepository extends AbstractGenericRepository implements UserRepositoryInterface, UserLoaderInterface
{


    public function getUserByIdentifier($identifier) {
        
        $qb = $this->getBuilder();
        $this->getUserByIdentifierQueryBuilder($qb, $identifier);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getUserByIdentifierQueryBuilder(QueryBuilder $qb, $identifier) {
        
            $qb->andWhere(
            $qb->expr()->orX(
                'u.username = :identifier', 'u.email = :identifier'
            )
        )
            ->setParameter('identifier', $identifier);
        return $this;
    }

    public function getUserByToken($token) {
        
                return $this->createQueryBuilder('u')
                ->where('u.confirmationToken = :confirmationToken')
                ->setParameter('confirmationToken', $token)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function loadUserByUsername($username) {
        
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
        
    }
    
    public function getAllNeverConnectedUsers()
    {
        $qb = $this->createQueryBuilder('u')
                ->where('u.lastTimeConnect is null' );
        
        return $qb->getQuery()->getResult();
    }
    
    public function getAllOldConnectedUsers(\DateTime $date)
    {
        $from =$date->format("2019-08-01").(" 00:00:00");
        $to = $date->format("2019-12-01").(" 23.59.59");
        
        $qb = $this->createQueryBuilder('u')
                ->where('u.lastTimeConnect BETWEEN :from AND :to')
                ->andWhere('u.isActive = 1')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        
        return $qb->getQuery()->getResult();
    }

}
