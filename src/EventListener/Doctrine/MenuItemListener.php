<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\EventListener\Doctrine;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuItemRepositoryInterface;

final class MenuItemListener
{
    /**
     * @var MenuItemRepositoryInterface
     */
    private MenuItemRepositoryInterface $menuItemRepository;

    /**
     * MenuItemListener constructor.
     *
     * @param MenuItemRepositoryInterface $menuItemRepository
     */
    public function __construct(MenuItemRepositoryInterface $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * @param MenuItemInterface $menuItem
     */
    public function prePersist(MenuItemInterface $menuItem): void
    {
        // Calculate Position
        if (null === $menuItem->getPosition()) {
            $parent = $menuItem->getParent();

            if (null === $parent) {
                $menu = $menuItem->getMenu();
                if (null === $menu) {
                    return;
                }
                $menuItem->setPosition($this->menuItemRepository->getLastPositionWithinMenu($menu) + 1);

                return;
            }

            $menuItem->setPosition($this->menuItemRepository->getLastPositionWithinMenuItem($parent) + 1);
        }
    }
}
