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

namespace MonsieurBiz\SyliusMenuPlugin\Manager;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use MonsieurBiz\SyliusMenuPlugin\Exception\IndexExceededException;

class MenuPositionHandler
{
    public const MOVE_UP = 'up';

    public const MOVE_DOWN = 'down';

    public function moveUp(MenuItemInterface $menuItem): void
    {
        try {
            $this->move($menuItem, self::MOVE_UP);
        } catch (IndexExceededException) {
            // Do nothing if the index is exceeded
        }
    }

    public function moveDown(MenuItemInterface $menuItem): void
    {
        try {
            $this->move($menuItem, self::MOVE_DOWN);
        } catch (IndexExceededException) {
            // Do nothing if the index is exceeded
        }
    }

    /**
     * @throws IndexExceededException
     */
    private function move(MenuItemInterface $menuItem, string $direction): void
    {
        $items = $this->getItems($menuItem);

        $index = array_search($menuItem, $items, true);
        if (\is_int($index)) {
            $indexToGo = $this->getNewIndex($index, \count($items) - 1, $direction);

            array_splice($items, $index, 1);
            array_splice($items, $indexToGo, 0, [$menuItem]);

            $position = 1;
            foreach ($items as $item) {
                $item->setPosition($position++);
            }
        }
    }

    /**
     * @throws IndexExceededException
     */
    private function getNewIndex(int $index, int $max, string $direction): int
    {
        $indexToGo = $index + (self::MOVE_UP === $direction ? -1 : 1);

        if ($indexToGo < 0 || $indexToGo > $max) {
            throw new IndexExceededException();
        }

        return $indexToGo;
    }

    /**
     * @return MenuItemInterface[]
     */
    private function getItems(MenuItemInterface $resource): array
    {
        $items = [];
        $parent = $resource->getParent();

        if (null !== $parent) {
            $resourceItems = $parent->getItems();
            if (null !== $resourceItems) {
                $items = $resourceItems->toArray();
            }

            return $items;
        }

        $menu = $resource->getMenu();
        if (null !== $menu) {
            $items = $menu->getFirstLevelItems();
        }

        return $items;
    }
}
