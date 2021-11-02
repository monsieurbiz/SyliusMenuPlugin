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

namespace MonsieurBiz\SyliusMenuPlugin\Hydrator;

use Doctrine\Common\Collections\Collection;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;

final class MenuTreeHydrator
{
    public function __invoke(?MenuInterface $menu): ?MenuInterface
    {
        if (null !== $menu) {
            foreach ($menu->getFirstLevelItems() as $menuItem) {
                $this->fillItems($menuItem);
            }
        }

        return $menu;
    }

    private function fillItems(MenuItemInterface $item): void
    {
        if (null === $item->getMenu() || null === $item->getMenu()->getItems()) {
            return;
        }
        $items = $this->filterItemsByParent(
            $item->getMenu()->getItems(),
            $item
        );
        $item->setItems($items);
    }

    /**
     * @param Collection<int, MenuItemInterface> $items
     *
     * @return Collection<int, MenuItemInterface>
     */
    private function filterItemsByParent(Collection $items, MenuItemInterface $parentItem): Collection
    {
        return $items->filter(function (MenuItemInterface $subItem) use ($parentItem): bool {
            if (null === $subItem->getParent()) {
                return false;
            }
            if ($subItem->getParent()->getId() === $parentItem->getId()) {
                $this->fillItems($subItem);

                return true;
            }

            return false;
        });
    }
}
