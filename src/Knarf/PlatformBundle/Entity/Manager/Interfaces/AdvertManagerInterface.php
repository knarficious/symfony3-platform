<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Entity\Manager\Interfaces;

use Knarf\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use Knarf\PlatformBundle\Entity\Advert;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 * @author franck
 */
interface AdvertManagerInterface extends GenericManagerInterface
{
    /**
     * @param int $limit
     * @param int $offset
     * @return array of adverts
     */
    public function getResultFilterPaginated($requestVal, $limit = 20, $offset = 0);
    
    /**
     * @param $requestVal
     * @return integer
     */
    public function getResultFilterCount($requestVal);
    
    /**
     * @param Advert $advert
     * @return FormInterface
     */
    public function getAdvertSearchForm(Advert $advert);
    
    /**
     * @param string $searchFormType
     * @return AdvertManagerInterface
     */
    public function setSearchFormType($searchFormType);
    
    /**
     * @param FormFactoryInterface $formFactory
     * @return AdvertManagerInterface
     */
    public function setFormFactory($formFactory);
    
    /**
     * @param RouterInterface $router
     * @return AdvertManagerInterface
     */
    public function setRouter($router); 
}
