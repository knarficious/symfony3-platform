<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Listeners;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Knarf\UserBundle\Entity\Avatar;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * Description of AvatarSubscriber
 *
 * @author franck
 */
class AvatarSubscriber implements EventSubscriberInterface
{
    private $cacheManager;
    
    public function __construct(CacheManager $cacheManager) 
    {
        $this->cacheManager = $cacheManager;
    }
    
    public function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        $visitor = $event->getVisitor();
        $path = $this->cacheManager->getBrowserPath($object, 'my_thumb');
        $visitor->setData('path', $path);
    }
    
    public static function getSubscribedEvents(): array {
        
        return array(
            array(
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => Avatar::class
                )
        );
        
    }

}
