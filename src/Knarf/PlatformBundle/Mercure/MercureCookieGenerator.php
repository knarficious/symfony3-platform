<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Mercure;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Knarf\UserBundle\Entity\App_User;

/**
 * Description of MercureCookieGenerator
 *
 * @author franck
 */
class MercureCookieGenerator
{
    /**
     * @var string 
     */
    private $secret;
    
    public function __construct(string $secret) 
    {
        $this->secret = $secret;
    }
    
    public function generate(App_User $user) 
    {
        $token = (new Builder())
                ->set('mercure', ['subscribe' => ["http://knarfmedia.local/user/{$user->getId()}"]])
//                ->set('mercure', ['publish' => ["http://knarfmedia.local/user/{$user->getId()}"]])
                ->sign(new Sha256(), $this->secret)
                ->getToken();
                
        return "mercureAuthorization={$token}; Path=/.well-known/mercure; Secure; HttpOnly; SameSite=strict";    
    }
}
