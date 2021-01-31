<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Controller;

define('HUB_URL', 'http://localhost:3000/hub');
//define('JWT', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.jRb2NCmejd6PA_32yqe4b10jjlj9PPfLQLeu3ZEH3L4');

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
//use Hhxsv5\SSE\SSE;
//use Hhxsv5\SSE\Update;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
use Knarf\PlatformBundle\Mercure\JwtProvider;
use Knarf\UserBundle\Entity\App_User;
use Knarf\PlatformBundle\Service\MercureCookieGenerator;

/**
 * Description of ChatController
 *
 * @author franck
 */
class ChatController extends Controller 
{
    /**
     *
     * @var string 
     */
    private $jwt;    
    
    
    public function __construct(JwtProvider $jwt) 
    {
        $this->jwt = $jwt;
        $jwt();        
        
    }
    
    /**
     * @Route("/chat", name="knarf_chat")
     * @return $response
     */
    public function index(MercureCookieGenerator $cookieGenerator)
    {        
        if ($this->denyAccessUnlessGranted('ROLE_USER', null, 'U must BE logged IN !!!'))
        {
            $response = $this->redirectToRoute('security_login_form');
        }
        
        $cookie = $cookieGenerator->generate($this->getUser());
        
        $response = $this->render('KnarfPlatformBundle:Chat:chat.html.twig', [
            'config' => [
                'topic' => 'chat',
                'publishRoute' => $this->generateUrl('publisher', ['topic' => 'chat'])
            ]
        ]);
        
        $response->headers->set('set-cookie', $cookie);
        
        return $response;
    }
    
    /**
     * @Route("/publish/{topic}", name="publisher", methods={"POST"})
     * @param type $topic
     * @param Request $request
     * @param App_User $user 
     * @return Response
     */
    public function publishAction($topic, Request $request, App_User $user = null)
    {
        $target = [];
            if($user !== null)
            {
                $target = [
                    "http://knarfmedia.local/user/{$user->getId()}"
                ];
            }
        
        //$secret = $this->get('knarf.platform.jwt.generator');
        $publisher = new Publisher(HUB_URL, $this->jwt);
                $publisher(new Update($topic, $request->getContent(), $target));
        return new Response('success');
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
         * @Route("/ping/{user}", name="ping", methods={"GET, POST"})
         * @return Response         * 
         */
        public function ping(App_User $user = null) 
        {
            $target = [];
            if($user !== null)
            {
                $target = [
                    "http://knarfmedia.local/user/{$user->getId()}"
                ];
            }
            $publisher = new Publisher(HUB_URL, new $this->jwt);
            $publisher(new Update("https://symfony.local/ping", "[]", $target));
            
            return $this->redirectToRoute('knarf_chat');
            
        }
}
