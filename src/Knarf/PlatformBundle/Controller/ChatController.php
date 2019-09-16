<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Controller;

//define('HUB_URL', 'http://localhost:3000/hub');
//define('JWT', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.NFCEbEEiI7zUxDU2Hj0YB71fQVT8YiQBGQWEyxWG0po');

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
//use Hhxsv5\SSE\SSE;
//use Hhxsv5\SSE\Update;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Jwt\StaticJwtProvider;

/**
 * Description of ChatController
 *
 * @author franck
 */
class ChatController extends Controller 
{
    /**
     * @Route("/chat", name="knarf_chat")
     * @return type
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'U must BE logged IN !!!');        
        
        return $this->render('KnarfPlatformBundle:Chat:chat.html.twig', [
            'config' => [
                'topic' => 'chat',
                'publishRoute' => $this->generateUrl('publisher', ['topic' => 'chat'])
            ]
        ]);
    }
    
    /**
     * @Route("/sse", name="sse")
     * @return StreamedResponse
     */
    public function newMsgsAction() 
    {
        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');//Nginx: unbuffered responses suitable for Comet and HTTP streaming applications
        $response->setCallback(function () {
            (new SSE())->start(new Update(function () {
                $id = mt_rand(1, 1000);
                $newMsgs = [['id' => $id, 'title' => 'title' . $id, 'content' => 'content' . $id]];//get data from database or service.
                if (!empty($newMsgs)) {
                    return json_encode(['newMsgs' => $newMsgs]);
                }
                return false;//return false if no new messages
            }), 'new-msgs');
        });
        return $response;
        }
        
        /**
         * @Route("/ping", name="ping", methods={"POST"})
         */
        public function ping() 
        {
            $publisher = new Publisher(HUB_URL, new StaticJwtProvider(JWT));
            $publisher(new Update("http://symfony.local/ping", "[]"));
            
            return $this->redirectToRoute('knarf_chat');
            
        }
}
