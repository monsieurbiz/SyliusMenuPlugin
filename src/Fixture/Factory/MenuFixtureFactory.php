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

namespace MonsieurBiz\SyliusMenuPlugin\Fixture\Factory;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemTranslationInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MenuFixtureFactory extends AbstractExampleFactory implements MenuFixtureFactoryInterface
{
    private FactoryInterface $menuFactory;

    private FactoryInterface $menuItemFactory;

    private FactoryInterface $menuItemTranslationFactory;

    private OptionsResolver $optionsResolver;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    /**
     * MenuFixtureFactory constructor.
     */
    public function __construct(
        FactoryInterface $menuFactory,
        FactoryInterface $menuItemFactory,
        FactoryInterface $menuItemTranslationFactory,
        SlugGeneratorInterface $slugGenerator
    ) {
        $this->menuFactory = $menuFactory;
        $this->menuItemFactory = $menuItemFactory;
        $this->slugGenerator = $slugGenerator;
        $this->menuItemTranslationFactory = $menuItemTranslationFactory;
        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->getOptionsResolver());
    }

    public function getOptionsResolver(): OptionsResolver
    {
        return $this->optionsResolver;
    }

    public function create(array $options = []): MenuInterface
    {
        $options = $this->optionsResolver->resolve($options);
        /** @var MenuInterface $menu */
        $menu = $this->menuFactory->createNew();
        $menu->setIsSystem($options['isSystem']);
        $menu->setCode($options['code']);
        $position = 1;

        foreach ($options['items'] as $item) {
            $this->createMenuItem($item, $position++, $menu);
        }

        return $menu;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('isSystem', true)
            ->setDefault('code', null)
            ->setDefault('items', [])
        ;
    }

    private function createMenuItem(array $item, int $position, MenuInterface $menu, ?MenuItemInterface $parentItem = null): MenuItemInterface
    {
        $item['children'] ??= [];
        /** @var MenuItemInterface $menuItem */
        $menuItem = $this->menuItemFactory->createNew();
        $menuItem->setPosition($position);
        $menuItem->setMenu($menu);

        if (null !== $parentItem) {
            $menuItem->setParent($parentItem);
        }

        foreach ($item['translations'] as $locale => $translation) {
            /** @var MenuItemTranslationInterface $menuItemTranslation */
            $menuItemTranslation = $this->menuItemTranslationFactory->createNew();
            $menuItemTranslation->setLabel($translation['label']);
            $menuItemTranslation->setUrl($translation['url']);
            $menuItemTranslation->setLocale($locale);
            $menuItem->addTranslation($menuItemTranslation);
        }
        $childPosition = 1;

        foreach ($item['children'] as $child) {
            $this->createMenuItem($child, $childPosition++, $menu, $menuItem);
        }

        return $menuItem;
    }
}
