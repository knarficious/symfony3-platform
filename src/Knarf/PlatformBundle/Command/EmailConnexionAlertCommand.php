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
use Knarf\UserBundle\Services\SendMailToNeverConnectedUserService;

/**
 * Description of EmailConnexionAlertCommand
 *
 * @author franck
 */
class EmailConnexionAlertCommand extends Command
{
    private $sendMail;
    
    public function __construct(SendMailToNeverConnectedUserService $sendMail) 
    {
        $this->sendMail = $sendMail;
        
        parent::__construct();
    }
    
    protected function configure() 
    {
        $this->setName('knarf:email-user')
             ->setDescription('Send an email to not yet connected users');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sendMail->sendMailToUsers();
    }
}
