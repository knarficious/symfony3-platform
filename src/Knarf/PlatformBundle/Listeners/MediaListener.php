<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Knarf\PlatformBundle\Entity\Media;
//use AppBundle\Services\ImageTransformer;
use Vich\UploaderBundle\Event\Event;
use Doctrine\ORM\EntityManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Description of MediaListener
 *
 * @author franck
 */
class MediaListener 
{
    private $cacheManager;
    private $vichUploaderHelper;
   // private $orm;
    
    public function __construct(CacheManager $cacheManager, UploaderHelper $vichUploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->vichUploaderHelper = $vichUploaderHelper;
    //    $this->orm = $orm;
    }

    public function onVichUploaderPreRemove(Event $event)
    {
        $entity= $event->getObject();
        $browserPath= $this->vichUploaderHelper->asset($entity, 'mediaFile');
        $this->cacheManager->remove($browserPath);
    //    $this->orm->remove($entity);
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Media) {
        // clear cache of thumbnail
        $this->cacheManager->remove($entity->getUploadDir());
     //   $this->orm->remove($entity);
        }
    }

    // when delete entity so remove all thumbnails related 
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Media) {

        $this->cacheManager->remove($entity->getWebPath());
//        $this->orm->remove($entity);
//        $this->orm->flush();
       }
    }
}
