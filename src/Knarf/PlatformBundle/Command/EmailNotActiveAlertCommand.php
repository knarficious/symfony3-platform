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
use Knarf\UserBundle\Services\SendMailToNotActiveUserService;


/**
 * Description of EmailNotActiveAlertCommand
 *
 * @author franck
 */
class EmailNotActiveAlertCommand extends Command
{
    private $sendMail;
    
    public function __construct(SendMailToNotActiveUserService $sendMail) 
    {
        $this->sendMail = $sendMail;
        
        parent::__construct();
    }
    
    protected function configure() 
    {
        $this->setName('knarf:email-not-active')
             ->setDescription('Send an email to not active users');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sendMail->sendMailToNotActiveUsers();
    }
}
