<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Services;

use Lcobucci\JWT\Builder;
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
                ->set('mercure', ['subscribe' => ["chat"]])
                ->sign(new \Lcobucci\JWT\Signer\Hmac\Sha256(), $this->secret)
                ->getToken();
                
        return "mercureAuthorization={$token}; Path=/hub; HttpOnly;";    
    }
}
