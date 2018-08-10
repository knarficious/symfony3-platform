<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Services\Interfaces;

/**
 *
 * @author franck
 */
interface MailerServiceInterface 
{
    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $body
     * @param string $charset
     * @param string $contentType
     */
    public function sendMail($from, $to, $subject, $body, $charset = 'UTF-8', $contentType = 'text/html');
}
