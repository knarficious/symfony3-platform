<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Description of AuthenticationFailureHandler
 *
 * @author franck
 */
class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler 
{
        public function __construct(
            HttpKernelInterface $httpKernel,
            HttpUtils $httpUtils,
            array $options,
            LoggerInterface $logger = null
    ) {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->logger->error('authentication error with user ' . $request->request->get('_username'));
        return parent::onAuthenticationFailure($request, $exception);
    }
}
