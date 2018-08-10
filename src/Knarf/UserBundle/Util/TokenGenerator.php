<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Util;

/**
 * Description of TokenGenerator
 *
 * @author franck
 */
class TokenGenerator implements TokenGeneratorInterface{
    
    public function generateToken() {
        
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        
    }

}
