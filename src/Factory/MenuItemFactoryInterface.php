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

interface MenuItemFactoryInterface extends FactoryInterface
{
    public function createNew(): object;

    public function createForMenu(MenuInterface $menu): MenuItemInterface;

    public function createForParent(MenuItemInterface $parent): MenuItemInterface;
}
