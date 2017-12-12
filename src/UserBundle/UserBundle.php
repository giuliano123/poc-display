<?php

namespace UserBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use UserBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

class UserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public function build(ContainerBuilder $container)
    {

        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());

    }
}
