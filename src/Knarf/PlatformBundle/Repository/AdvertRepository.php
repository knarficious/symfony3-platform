<?php

namespace Knarf\PlatformBundle\Repository;


use Doctrine\ORM\QueryBuilder;
use Knarf\CoreBundle\Repository\AbstractGenericRepository;
use Knarf\PlatformBundle\Entity\Advert;
use Knarf\PlatformBundle\Entity\Rubrique;
use Knarf\PlatformBundle\Repository\Interfaces\AdvertRepositoryInterface;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends AbstractGenericRepository implements AdvertRepositoryInterface
{

    public function getQueryResultFilter($requestVal) {
        
    }

    public function getResultFilterCount($requestVal) {
        
    }

    public function getResultFilterPaginated($requestVal, $limit = 20, $offset = 0) {
        
    }
    
    public function getLastAdverts()
    {
        $qb = $this->createQueryBuilder('a')
                ->setMaxResults(6);
        
        return $qb->getQuery()->getResult();
    }
    
    


}
