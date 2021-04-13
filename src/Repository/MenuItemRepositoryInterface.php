<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Repository;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface MenuItemRepositoryInterface extends RepositoryInterface
{
    /**
     * @param MenuInterface $menu
     *
     * @return int
     */
    public function getLastPositionWithinMenu(MenuInterface $menu): int;

    /**
     * @param MenuItemInterface $menuItem
     *
     * @return int
     */
    public function getLastPositionWithinMenuItem(MenuItemInterface $menuItem): int;
}
