<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
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

class MenuFixtureFactory extends AbstractExampleFactory implements MenuFixtureFactoryInterface
{
    private FactoryInterface $menuFactory;

    private FactoryInterface $menuItemFactory;

    private FactoryInterface $menuItemTranslationFactory;

    private OptionsResolver $optionResolver;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    /**
     * MenuFixtureFactory constructor.
     *
     * @param FactoryInterface $menuFactory
     * @param FactoryInterface $menuItemFactory
     * @param FactoryInterface $menuItemTranslationFactory
     * @param SlugGeneratorInterface $slugGenerator
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
        $this->optionResolver = new OptionsResolver();
        $this->configureOptions($this->optionResolver);
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param array $options
     *
     * @return MenuInterface
     */
    public function create(array $options = []): MenuInterface
    {
        $options = $this->optionResolver->resolve($options);
        /** @var MenuInterface $menu */
        $menu = $this->menuFactory->createNew();
        $menu->setIsSystem($options['isSystem']);
        $menu->setCode($options['code']);
        $position = 1;

        foreach ($options['items'] as $item) {
            /** @var MenuItemInterface $menuItem */
            $menuItem = $this->menuItemFactory->createNew();
            $menuItem->setPosition($position++);
            $menuItem->setMenu($menu);

            foreach ($item['translations'] as $locale => $translation) {
                /** @var MenuItemTranslationInterface $menuItemTranslation */
                $menuItemTranslation = $this->menuItemTranslationFactory->createNew();
                $menuItemTranslation->setLabel($translation['label']);
                $menuItemTranslation->setUrl($translation['url']);
                $menuItemTranslation->setLocale($locale);
                $menuItem->addTranslation($menuItemTranslation);
            }
        }

        return $menu;
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('isSystem', true)
            ->setDefault('code', null)
            ->setDefault('items', [])
        ;
    }
}
