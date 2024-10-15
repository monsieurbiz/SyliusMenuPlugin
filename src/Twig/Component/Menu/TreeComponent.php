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

namespace MonsieurBiz\SyliusMenuPlugin\Twig\Component\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItem;
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuItemRepository;
use MonsieurBiz\SyliusMenuPlugin\Manager\MenuPositionHandler;
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuRepository;
use Sylius\Bundle\AdminBundle\Doctrine\Query\Taxon\AllTaxonsInterface;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceLivePropTrait;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\TwigHooks\LiveComponent\HookableLiveComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use MonsieurBiz\SyliusMenuPlugin\DataProvider\Tree\MenuItemTreeProvider;
use MonsieurBiz\SyliusMenuPlugin\Entity\Menu;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent]
class TreeComponent
{
    use DefaultActionTrait;
    use HookableLiveComponentTrait;

    public ?ResourceInterface $resource = null;

    public function __construct(
        private readonly MenuItemTreeProvider $treeDataProvider,
        private readonly MenuPositionHandler $menuPositionHandler,
        private readonly MenuItemRepository $menuItemRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getTree(): array
    {
        $resource = $this->resource;

        if ($resource !== null) {
            if ($resource instanceof MenuItem) {
                $resource = $resource->getMenu();
            }

            return $this->buildTree(
                $this->treeDataProvider->getArrayResult(
                    $resource,
                )
            );
        }

        return [];
    }

    #[LiveAction]
    public function moveUp(#[LiveArg] int $menuItemId): void
    {
        $menuItem = $this->menuItemRepository->find($menuItemId);

        if ($menuItem === null) {
            return;
        }

        $this->menuPositionHandler->moveUp($menuItem);
        $this->entityManager->flush();

        $this->resource = $menuItem->getMenu();
    }

    #[LiveAction]
    public function moveDown(#[LiveArg] int $menuItemId): void
    {
        $menuItem = $this->menuItemRepository->find($menuItemId);

        if ($menuItem === null) {
            return;
        }

        $this->menuPositionHandler->moveDown($menuItem);
        $this->entityManager->flush();

        $this->resource = $menuItem->getMenu();
    }

    #[LiveAction]
    public function deleteItem(#[LiveArg] int $menuItemId): void
    {
        $menuItem = $this->menuItemRepository->find($menuItemId);

        if ($menuItem === null) {
            return;
        }

        $this->menuItemRepository->remove($menuItem);
        $this->entityManager->flush();

        $this->resource = $menuItem->getMenu();
    }

    private function buildTree(array $menuItems): array
    {
        $tree = [];
        $children = [];

        foreach ($menuItems as $menuItem) {
            $treeChild = [
                'id' => $menuItem['id'],
                'name' => $menuItem['name'],
                'menu_id' => $menuItem['menu_id'],
                'children' => $children[$menuItem['id']] ?? [],
            ];

            if (null !== $menuItem['parent_id']) {
                $children[$menuItem['parent_id']][] = $treeChild;
            } else {
                $tree[] = $treeChild;
            }
        }

        return $tree;
    }
}
