<?php

namespace Knarf\PlatformBundle\Controller;

define('HUB_URL', 'http://localhost:3000/hub');
define('JWT', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.NFCEbEEiI7zUxDU2Hj0YB71fQVT8YiQBGQWEyxWG0po');


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\Jwt\StaticJwtProvider;

class PublisherController extends Controller
{
    /**
     * @Route("/publish/{topic}", name="publisher", methods={"POST"})
     * @param type $topic
     * @param Request $request
     * @return Response
     */
    public function indexAction($topic, Request $request)
    {
        $publisher = new Publisher(HUB_URL, new StaticJwtProvider(JWT));
                $publisher(new Update($topic, $request->getContent()));
        return new Response('success');
    }
}
