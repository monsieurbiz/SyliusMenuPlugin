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
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;
use Webmozart\Assert\Assert;

final class MenuExtension extends AbstractExtension implements ExtensionInterface
{
    /**
     * @var MenuRepositoryInterface
     */
    private MenuRepositoryInterface $menuRepository;

    /**
     * @var array
     */
    private array $menus = [];

    /**
     * @var LocaleContextInterface
     */
    private LocaleContextInterface $localeContext;

    /**
     * MenuExtension constructor.
     *
     * @param RepositoryInterface $menuRepository
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(
        RepositoryInterface $menuRepository,
        LocaleContextInterface $localeContext
    ) {
        Assert::isInstanceOf($menuRepository, MenuRepositoryInterface::class);
        $this->menuRepository = $menuRepository;
        $this->localeContext = $localeContext;
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
            $menu = $this->menuRepository->findOneByLocaleAndCode($this->localeContext->getLocaleCode(), $menuCode);
            if (null === $menu) {
                return [];
            }
            $this->menus[$menuCode] = $menu;
        }

        return $this->menus[$menuCode]->getFirstLevelItems();
    }
}
