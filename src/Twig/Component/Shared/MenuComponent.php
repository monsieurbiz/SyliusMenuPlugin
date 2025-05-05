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

namespace MonsieurBiz\SyliusMenuPlugin\Twig\Component\Shared;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'monsieurbiz:shared:menu', template: '@SyliusUi/components/default.html.twig')]
final class MenuComponent
{
    /**
     * @var MenuItemInterface[]|null
     */
    private ?array $menuItems = null;

    public function __construct(
        public MenuRepositoryInterface $menuRepository,
        public LocaleContextInterface $localeContext,
    ) {
    }

    public function getMenuItems(string $menuCode): array
    {
        if (null === $this->menuItems) {
            $this->menuItems = $this->loadMenuItems($menuCode);
        }

        return $this->menuItems;
    }

    /**
     * @return MenuItemInterface[]
     */
    private function loadMenuItems(string $menuCode): array
    {
        $menu = $this->menuRepository->findOneByLocaleAndCode($this->localeContext->getLocaleCode(), $menuCode);
        if (null === $menu) {
            return [];
        }

        return $menu->getFirstLevelItems();
    }
}
