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
use Knarf\UserBundle\Services\SendMailToNeverConnectedUserService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of EmailConnexionAlertCommand
 *
 * @author franck
 */
class EmailConnexionAlertCommand extends ContainerAwareCommand
{
    private $sendMail;
    private $em;
    
    public function __construct(EntityManagerInterface $em, SendMailToNeverConnectedUserService $sendMail) 
    {
        $this->em = $em;
        $this->sendMail = $sendMail;
        
        parent::__construct();
    }
    
    protected function configure() 
    {
        $this->setName('knarf:email-user')
             ->setDescription('Send an email to never connected users');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sendMail->sendMailToUsers();
    }
}
