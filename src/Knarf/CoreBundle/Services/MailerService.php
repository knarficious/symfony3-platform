<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Services;

use Knarf\CoreBundle\Services\Interfaces\MailerServiceInterface;


/**
 * Description of MailerService
 *
 * @author franck
 */
class MailerService implements MailerServiceInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;
    
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    /**
     * @inheritdoc
     */
    public function sendMail($from, $to, $subject, $body, $charset = 'UTF-8', $contentType = 'text/html') 
    {
        $message = $this->createMessageInstance($from, $to, $subject, $body, $charset, $contentType);
        $this->mailer->send($message);
    }
    
     private function createMessageInstance($from, $to, $subject, $body, $charset = 'UTF-8', $contentType = 'text/html')
    {
        $message = \Swift_Message::newInstance()
            ->setCharset($charset)
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)                            
            ->setContentType($contentType);
        $cid = $message->embed(\Swift_Image::fromPath('https://blog.franckruer.fr/images/8-cell-simple.gif'));
        $message->setBody($body . '<img src="' . $cid. '" alt="knarf media" />');
                
        return $message;
    }

}
