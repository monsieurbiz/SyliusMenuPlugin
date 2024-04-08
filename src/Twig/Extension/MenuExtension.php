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

namespace MonsieurBiz\SyliusMenuPlugin\Twig\Extension;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;
use Webmozart\Assert\Assert;

final class MenuExtension extends AbstractExtension implements ExtensionInterface
{
    public const SYLIUS_TRANSLATION_BLOCK_PREFIX = 'sylius_translations';

    private MenuRepositoryInterface $menuRepository;

    private array $menus = [];

    private LocaleContextInterface $localeContext;

    /**
     * MenuExtension constructor.
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
            new TwigFunction('get_locale_from_form', [$this, 'getLocaleFromForm']),
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

    public function getLocaleFromForm(FormView $form): ?string
    {
        $currentForm = $form;
        do {
            /**
             * `sylius_translations` block prefix is used in Sylius translation forms.
             * The child has the name of the current form locale.
             */
            $parentForm = $currentForm->parent;
            $blockPrefixes = $parentForm?->vars['block_prefixes'] ?? [];
            if (
                \in_array(self::SYLIUS_TRANSLATION_BLOCK_PREFIX, $blockPrefixes, true) // Check the parent is `sylius_translations`
                && null !== ($locale = $currentForm->vars['name'] ?? null) // Check the current form has a name (containing the locale code)
            ) {
                return $locale;
            }
        } while ($currentForm = $currentForm->parent);

        // Fallback on locale context
        return $this->localeContext->getLocaleCode();
    }
}
