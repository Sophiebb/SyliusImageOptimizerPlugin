<?php

declare(strict_types=1);

namespace Loevgaard\SyliusOptimizeImagesPlugin;

use Loevgaard\SyliusOptimizeImagesPlugin\DependencyInjection\Compiler\StorerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LoevgaardSyliusOptimizeImagesPlugin extends Bundle
{
    use SyliusPluginTrait;

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StorerPass());
    }
}
