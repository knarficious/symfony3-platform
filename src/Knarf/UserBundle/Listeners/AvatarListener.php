<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Knarf\UserBundle\Entity\User;
//use AppBundle\Services\ImageTransformer;
use Vich\UploaderBundle\Event\Event;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of AvatarListener
 *
 * @author franck
 */
class AvatarListener 
{
    private $cacheManager;
    private $vichUploaderStorage;
    
    public function __construct($cacheManager, $vichUploaderStorage)
    {
        $this->cacheManager = $cacheManager;
        $this->vichUploaderStorage = $vichUploaderStorage;
    }

    public function onVichUploaderPreRemove(Event $event)
    {
        $entity= $event->getObject();
        $browserPath= $this->vichUploaderStorage->asset($entity, 'mediaFile');
        $this->cacheManager->remove($browserPath);
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
        // clear cache of thumbnail
        $this->cacheManager->remove($entity->getUploadDir());
        }
    }

    // when delete entity so remove all thumbnails related 
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {

        $this->cacheManager->remove($entity->getWebPath());
        }
    }
}
