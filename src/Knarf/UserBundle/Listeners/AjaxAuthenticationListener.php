<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Listeners;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Description of AjaxAuthenticationListener
 *
 * @author franck
 */
class AjaxAuthenticationListener 
{
    /*
     * function onCoreException
     * Check if session is expired and handles security related exceptions  
     * @param GetResponseForExceptionEvent $event An GetResponseForExceptionEvent instance
     * 
     */
    public function onCoreException(GetResponseForExceptionEvent $event) {

        $exception = $event->getException();

        $event_request = $event->getRequest();

        $session = $event->getRequest()->getSession();

        if ($event_request->isXmlHttpRequest()) {

            if ($exception instanceof AuthenticationException || $exception instanceof AccessDeniedException) {

                $session->getFlashBag()->add('warning', 'You have been signed out automatically due to inactivity.');

                $event->setResponse(new Response('Session expired', 403));

            }
        }
    }
}