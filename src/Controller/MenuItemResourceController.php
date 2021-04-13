<?php

/*
 * This file is part of Monsieur Biz' Menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Controller;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

final class MenuItemResourceController extends ResourceController
{
    public const MOVE_UP = 'up';
    public const MOVE_DOWN = 'down';

    public function moveUpAction(Request $request, EntityManagerInterface $menuItemManager): Response
    {
        return $this->move($request, $menuItemManager, self::MOVE_UP);
    }

    public function moveDownAction(Request $request, EntityManagerInterface $menuItemManager): Response
    {
        return $this->move($request, $menuItemManager, self::MOVE_DOWN);
    }

    private function move(Request $request, EntityManagerInterface $menuItemManager, string $direction): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);

        /** @var MenuItemInterface $resource */
        $resource = $this->findOr404($configuration);

        $items = $this->getItems($resource);

        $index = array_search($resource, $items, true);
        if (\is_int($index)) {
            $indexToGo = $this->getNewIndex($index, \count($items) - 1, $direction);

            array_splice($items, $index, 1);
            array_splice($items, $indexToGo, 0, [$resource]);

            $position = 1;
            foreach ($items as $item) {
                $item->setPosition($position++);
                $menuItemManager->persist($item);
            }
            $menuItemManager->flush();
        }

        return new Response();
    }

    private function getNewIndex(int $index, int $max, string $direction): int
    {
        $indexToGo = $index + (self::MOVE_UP === $direction ? -1 : 1);

        if ($indexToGo < 0 || $indexToGo > $max) {
            throw new PreconditionFailedHttpException();
        }

        return $indexToGo;
    }

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
