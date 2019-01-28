<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Event;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Knarf\UserBundle\Entity\Avatar;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Description of AvatarUrlSubscriber
 *
 * @author franck
 */
class AvatarSubscriber implements EventSubscriberInterface
{
   private $uploaderHelper;
   
   public function __construct(UploaderHelper $uploaderHelper)
   {
       $this->uploaderHelper = $uploaderHelper;
   }
   
   public function onPostSerialize(ObjectEvent $event)
   {
       $object = $event->getObject();
       $visitor = $event->getVisitor();
       $path = $this->uploaderHelper->asset($object, 'mediaFile');
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
