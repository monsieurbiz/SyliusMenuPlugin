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

namespace MonsieurBiz\SyliusMenuPlugin\Factory;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class MenuItemFactory implements MenuItemFactoryInterface
{
    private FactoryInterface $decoratedFactory;

    /**
     * MenuItemFactory constructor.
     */
    public function __construct(
        FactoryInterface $decoratedFactory
    ) {
        $this->decoratedFactory = $decoratedFactory;
    }

    /**
     * @inheritdoc
     */
    public function createNew(): object
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForMenu(MenuInterface $menu): MenuItemInterface
    {
        /** @var MenuItemInterface $item */
        $item = $this->createNew();
        $item->setMenu($menu);

        return $item;
    }

    public function createForParent(MenuItemInterface $parent): MenuItemInterface
    {
        /** @var MenuItemInterface $item */
        $item = $this->createNew();
        $item->setParent($parent);
        $item->setMenu($parent->getMenu());

        return $item;
    }
}
