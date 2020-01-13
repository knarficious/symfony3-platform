<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Knarf\UserBundle\Services\SendMailToOldConnectedUserService;

/**
 * Description of EmailConnexionAlertCommand
 *
 * @author franck
 */
class EmailOldConnexionAlertCommand extends Command
{
    private $sendMail;
    
    public function __construct(SendMailToOldConnectedUserService $sendMail) 
    {
        $this->sendMail = $sendMail;
        
        parent::__construct();
    }
    
    protected function configure() 
    {
        $this->setName('knarf:email-user-old-connect')
             ->setDescription('Send an email to old connected users');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sendMail->sendMailToUsers();
    }
}
