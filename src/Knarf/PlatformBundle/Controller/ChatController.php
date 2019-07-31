<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        
        return $this->render('KnarfPlatformBundle:Chat:customized-chat.html.twig', ['ws_url' => '127.0.0.1:8080', 'knarf_website' => 'https://franckruer.fr']);
    }
}
