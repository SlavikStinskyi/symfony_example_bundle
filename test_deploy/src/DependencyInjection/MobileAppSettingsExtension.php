<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\DependencyInjection;

use GinCms\Bundle\TestBundle\StaticTestEnv;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class MobileAppSettingsExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $loader->load('services.yaml');

        /** @var array<string, string> $bundles */
        $bundles = $container->getParameter('kernel.bundles');
        if (true === \array_key_exists('GraphQLiteBundle', $bundles)) {
            $loader->load('services_gql.yaml');
        }

        if ('test' === $container->getParameter('kernel.environment') && 'mobile-app-settings' === StaticTestEnv::getEnv()) {
            $loader->load('services_test.yaml');
        }
    }
}
