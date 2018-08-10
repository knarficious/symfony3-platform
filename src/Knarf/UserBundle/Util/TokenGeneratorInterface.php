<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Util;

/**
 *
 * @author franck
 */
interface TokenGeneratorInterface {
    
    /**
     * @return string
     */
    public function generateToken();
}
