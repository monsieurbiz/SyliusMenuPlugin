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

namespace MonsieurBiz\SyliusMenuPlugin\Twig\Component\Menu;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusMenuPlugin\DataProvider\Tree\MenuItemTreeProviderInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use MonsieurBiz\SyliusMenuPlugin\Manager\MenuPositionHandler;
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuItemRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\TwigHooks\LiveComponent\HookableLiveComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'monsieurbiz:menu:tree', template: '@MonsieurBizSyliusMenuPlugin/admin/menu/tree.html.twig')]
class TreeComponent
{
    use DefaultActionTrait;
    use HookableLiveComponentTrait;

    public ?ResourceInterface $resource = null;

    #[LiveProp(writable: true)]
    public array $currentMenuItem = [];

    public function __construct(
        private readonly MenuItemTreeProviderInterface $treeDataProvider,
        private readonly MenuPositionHandler $menuPositionHandler,
        private readonly MenuItemRepository $menuItemRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getTree(): array
    {
        $resource = $this->resource;
        if (!$resource instanceof MenuInterface) {
            return [];
        }

        return $this->buildTree(
            $this->treeDataProvider->getArrayResult(
                $resource,
            )
        );
    }

    #[LiveAction]
    public function moveUp(#[LiveArg] int $menuItemId): void
    {
        /** @var ?MenuItemInterface $menuItem */
        $menuItem = $this->menuItemRepository->find($menuItemId);

        if (null === $menuItem) {
            return;
        }

        $this->menuPositionHandler->moveUp($menuItem);
        $this->entityManager->flush();

        $this->resource = $menuItem->getMenu();
    }

    #[LiveAction]
    public function moveDown(#[LiveArg] int $menuItemId): void
    {
        /** @var ?MenuItemInterface $menuItem */
        $menuItem = $this->menuItemRepository->find($menuItemId);
        if (null === $menuItem) {
            return;
        }

        $this->menuPositionHandler->moveDown($menuItem);
        $this->entityManager->flush();

        $this->resource = $menuItem->getMenu();
    }

    private function buildTree(array $menuItems): array
    {
        $tree = [];
        $children = [];

        $mainIndex = 0;
        $nbMainMenuItems = \count(array_filter($menuItems, static fn (array $item) => null === $item['parent_id']));
        foreach ($menuItems as $menuItem) {
            $treeChild = [
                'id' => $menuItem['id'],
                'name' => $menuItem['name'],
                'menu_id' => $menuItem['menu_id'],
                'children' => $children[$menuItem['id']] ?? [],
            ];

            if (null !== $menuItem['parent_id']) {
                $this->markFirstAndLast($treeChild['children']);
                $children[$menuItem['parent_id']][] = $treeChild;

                continue;
            }

            $treeChild['is_first'] = 0 === $mainIndex;
            $treeChild['is_last'] = $mainIndex === $nbMainMenuItems - 1;

            $this->markFirstAndLast($treeChild['children']);

            $tree[] = $treeChild;
            ++$mainIndex;
        }

        return $tree;
    }

    private function markFirstAndLast(array &$items): void
    {
        $count = \count($items);
        foreach ($items as $index => &$item) {
            $item['is_first'] = (0 === $index);
            $item['is_last'] = ($index === $count - 1);
        }
    }
}
