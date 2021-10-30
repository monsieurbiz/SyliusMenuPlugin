<?php

/*
 * This file is part of Monsieur Biz' Menu plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusMenuPlugin\Fixture\Factory\MenuFixtureFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class MenuFixture extends AbstractResourceFixture
{
    public function __construct(EntityManagerInterface $menuManager, MenuFixtureFactoryInterface $exampleFactory)
    {
        parent::__construct($menuManager, $exampleFactory);
    }

    public function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        /** @phpstan-ignore-next-line */
        $resourceNode
            ->children()
                ->booleanNode('isSystem')->end()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->arrayNode('items')
                    ->defaultValue([])
                        ->arrayPrototype()
                            ->children()
                                ->arrayNode('translations')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('label')->cannotBeEmpty()->end()
                                            ->scalarNode('url')->defaultValue('')->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->variableNode('children')->cannotBeEmpty()->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;
    }

    public function getName(): string
    {
        return 'monsieurbiz_menu';
    }
}
