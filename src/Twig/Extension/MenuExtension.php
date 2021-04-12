<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Twig\Extension;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

final class MenuExtension extends AbstractExtension implements ExtensionInterface
{
    /**
     * @var RepositoryInterface
     */
    private RepositoryInterface $menuRepository;

    /**
     * @var array
     */
    private array $menus = [];

    /**
     * MenuExtension constructor.
     *
     * @param RepositoryInterface $menuRepository
     */
    public function __construct(RepositoryInterface $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('menu_first_level', [$this, 'getMenuFirstLevelItems']),
        ];
    }

    public function getMenuFirstLevelItems(string $menuCode): ?array
    {
        if (!\array_key_exists($menuCode, $this->menus)) {
            /** @var ?MenuInterface $menu */
            $menu = $this->menuRepository->findOneBy(['code' => $menuCode]);
            if (null === $menu) {
                return [];
            }
            $this->menus[$menuCode] = $menu;
        }

        return $this->menus[$menuCode]->getFirstLevelItems();
    }
}
