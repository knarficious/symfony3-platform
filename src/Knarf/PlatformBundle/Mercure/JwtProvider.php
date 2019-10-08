<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Mercure;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
/**
 * Description of JwtProvider
 *
 * @author franck
 */
class JwtProvider 
{
    /**
     *
     * @var string 
     */
    private $secret;
    
    public function __construct(string $secret) 
    {
        $this->secret = $secret;
    }
    
    public function __invoke(): string 
    {
        return (new Builder())
            ->set('mercure', ['publish' => ["*"]])
            ->sign(new Sha256(), $this->secret)
            ->getToken();
    }
}
