<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PaginateDirectorySubscriber
 *
 * @author franck
 */

// file: src/Knarf/PlatformBundle/Subscriber/PaginateDirectorySubscriber.php
// requires // Symfony\Component\Finder\Finder

namespace Knarf\PlatformBundle\Subscriber;

use Symfony\Component\Finder\Finder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Knp\Component\Pager\Event\ItemsEvent;

class PaginateDirectorySubscriber implements EventSubscriberInterface
{
    public function items(ItemsEvent $event)
    {
        if (is_string($event->target) && is_dir($event->target)) {
            $finder = new Finder;
            $finder
                ->files()
                ->depth('< 4') // 3 levels
                ->in($event->target)
            ;
            $iter = $finder->getIterator();
            $files = iterator_to_array($iter);
            $event->count = count($files);
            $event->items = array_slice(
                $files,
                $event->getOffset(),
                $event->getLimit()
            );
            $event->stopPropagation();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'knp_pager.items' => ['items', 1/*increased priority to override any internal*/]
        ];
    }
}
