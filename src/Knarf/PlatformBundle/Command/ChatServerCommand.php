<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Knarf\PlatformBundle\Server\Chat;

/**
 * Description of ChatServerCommand
 *
 * @author franck
 */
class ChatServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('knarf:chat-server')
            ->setDescription('Start chat server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Chat socket',// A line
            '============',// Another line
            'Starting chat, open your browser.',// Empty line
        ]);
        
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8080
        );
        $server->run();
    }
}
