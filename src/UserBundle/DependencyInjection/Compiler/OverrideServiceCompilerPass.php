<?php

namespace UserBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use UserBundle\EventListener\AuthenticationListener;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_user.listener.authentication');
        $definition->setClass(AuthenticationListener::class);
    }
}