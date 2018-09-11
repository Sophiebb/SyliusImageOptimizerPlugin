<?php

declare(strict_types=1);

namespace Loevgaard\SyliusOptimizeImagesPlugin\DependencyInjection\Compiler;

use Loevgaard\SyliusOptimizeImagesPlugin\Storer\AsyncStorer;
use Loevgaard\SyliusOptimizeImagesPlugin\Storer\StorerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class StorerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($this->isEnqueueEnabled($container)) {
            $definition = new Definition(AsyncStorer::class, [new Reference('enqueue.producer')]);
            $container->setDefinition('loevgaard.sylius_optimize_images.storer.async_storer', $definition);

            $container->setAlias(StorerInterface::class, 'loevgaard.sylius_optimize_images.storer.async_storer');
        } else {
            $container->setAlias(StorerInterface::class, 'loevgaard.sylius_optimize_images.storer.sync_storer');
        }
    }

    /**
     * Will return true if the enqueue and liip imagine bundles are enabled AND the enqueue option is enabled
     *
     * @param ContainerBuilder $container
     *
     * @return bool
     */
    private function isEnqueueEnabled(ContainerBuilder $container): bool
    {
        if (!$container->hasExtension('liip_imagine') || !$container->hasExtension('enqueue')) {
            return false;
        }

        $liipImagineConfig = $container->getExtensionConfig('liip_imagine');

        foreach ($liipImagineConfig as $item) {
            if (isset($item['enqueue'])) {
                return true;
            }
        }

        return false;
    }
}
