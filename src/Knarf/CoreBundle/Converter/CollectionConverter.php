<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Knarf\CoreBundle\Converter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * Description of CollectionConverter
 *
 * @author franck
 */
class CollectionConverter implements \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var ManagerRegistry $registry Manager registry
     */
    private $registry;
    
    /**
     * CollectionConverter constructor.
     * @param ManagerRegistry $registry
     * @param ContainerInterface $container
     */
    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        $this->registry = $registry;
        $this->container = $container;
    }
    /**
     * {@inheritdoc}
     *
     * Check, if object supported by our converter
     */
    public function supports(ParamConverter $configuration)
    {
        return true;
    }
    /**
     * {@inheritdoc}
     *
     * Applies converting
     *
     * @throws \InvalidArgumentException When route attributes are missing
     * @throws NotFoundHttpException     When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        // http://stfalcon.com/en/blog/post/symfony2-custom-paramconverter
        // http://stackoverflow.com/questions/10904759/symfony2-and-paramconverters
        $name    = $configuration->getName();
        $options = $configuration->getOptions();
        $managerClassName = '';
        $dir = 'ASC';
        
        $max = $request->attributes->get('max', null);
        
        $orderby = "";
        if (!empty($options['orderby'])) {
            $orderby = $options['orderby'];
        }
        if (!empty($options['dir'])) {
            $dir = $options['dir'];
        }
        
        try {
            $managerClassName = $options['manager'];
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf('%s service manager option empty.', $managerClassName));
        }
        try {
            $manager = $this->container->get($managerClassName);
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('%s service manager not found.', $managerClassName));
        }
        try {
            $result = $manager->all("object", $max, $orderby, $dir);
            $collection = new ArrayCollection($result);
        } catch (\Exception $e) {
            throw new BadMethodCallException(sprintf('%s service manager method all not found.', $managerClassName));
        }
        
        if (!($collection instanceof ArrayCollection)) {
            throw new NotFoundHttpException(sprintf('%s objects not found.', $name));
        }
        $request->attributes->set($name, $collection);
    }
}
