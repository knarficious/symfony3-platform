<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Listeners;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Knarf\PlatformBundle\Entity\Media;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Description of MediaSubscriber
 *
 * @author franck
 */
class MediaSubscriber implements EventSubscriberInterface
{
    private $cacheManager;
    private $uploaderHelper;
    
    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper) 
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }
    
    public function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        $visitor = $event->getVisitor();
        $path = $this->cacheManager->getBrowserPath($object, 'carousel');
        $visitor->setData('path', $path);
    }
    
    public static function getSubscribedEvents(): array {
        
        return array(
            array(
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => Media::class
                )
        );
        
    }

}
