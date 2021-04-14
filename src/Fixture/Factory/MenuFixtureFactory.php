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
use MonsieurBiz\SyliusMenuPlugin\Repository\MenuItemRepository;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
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
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    public function __construct(FactoryInterface $menuFactory, FactoryInterface $menuItemFactory, FactoryInterface $menuItemTranslationFactory, SlugGeneratorInterface $slugGenerator, MenuItemRepository $menuItemRepository)
    {
        $this->menuFactory = $menuFactory;
        $this->menuItemFactory = $menuItemFactory;
        $this->slugGenerator = $slugGenerator;
        $this->menuItemTranslationFactory = $menuItemTranslationFactory;
        $this->optionResolver = new OptionsResolver();
        $this->configureOptions($this->optionResolver);
        $this->faker = \Faker\Factory::create();
        $this->menuItemRepository = $menuItemRepository;
    }

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
            $menuItem->setHighlighted($item['highlighted']);
            $menuItem->setPosition($position++);

            foreach ($item['translations'] as $locale => $translation) {
                /** @var MenuItemTranslationInterface $menuItemTranslation */
                $menuItemTranslation = $this->menuItemTranslationFactory->createNew();
                $menuItemTranslation->setLabel($translation['label']);
                $menuItemTranslation->setLocale($locale);
                $menuItem->addTranslation($menuItemTranslation);
            }

            $menu->addItem($menuItem);
        }

        return $menu;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('isSystem', function(Options $options): bool {
                return $this->faker->boolean(80);
            })
            ->setDefault('code', function(Options $options): string {
                return $this->slugGenerator->generate($this->faker->sentence(2, true));
            })
            ->setDefault('items', [])
        ;
    }
}
