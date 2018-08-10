<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;
/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);
return $loader;

