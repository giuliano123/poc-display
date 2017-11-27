<?php

namespace AdminBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'admin.aws3.configuration',
            array_key_exists('aws3', $config) ? $config['aws3']['configuration'] : array()
        );

        $container->setParameter(
            'admin.aws3.bucket',
            array_key_exists('aws3', $config) ? $config['aws3']['bucket'] : null
        );

        $container->setParameter(
            'admin.aws3.base_path',
            array_key_exists('aws3', $config) ? $config['aws3']['base_path'] : ''
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('services.yml');
    }
}