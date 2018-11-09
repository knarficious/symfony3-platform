<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\DependencyInjection\Compilerpass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of ManagerCompilerPass
 *
 * @author franck
 */
class ManagerCompilerPass implements CompilerPassInterface
{
   public function process(ContainerBuilder $container)
   {
      if (!$container->has('app.manager_container_service')) {
            return;
        }
        $definition = $container->findDefinition(
            'app.manager_container_service'
        );
        $taggedServices = $container->findTaggedServiceIds(
            'app.manager_services'
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addManager',
                array(new Reference($id))
            );
        }  
   }

}
