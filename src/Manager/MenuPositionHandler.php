<?php

/**
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Manager;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItem;
use MonsieurBiz\SyliusMenuPlugin\Exception\IndexExceededException;

class MenuPositionHandler
{
    public const MOVE_UP = 'up';

    public const MOVE_DOWN = 'down';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function moveUp(MenuItem $menuItem): void
    {
        try {
            $this->move($menuItem, self::MOVE_UP);
        } catch (IndexExceededException) {
        }
    }

    public function moveDown(MenuItem $menuItem)
    {
        try {
            $this->move($menuItem, self::MOVE_DOWN);
        } catch (IndexExceededException) {
        }
    }

    private function move(MenuItem $menuItem, string $direction): void
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
