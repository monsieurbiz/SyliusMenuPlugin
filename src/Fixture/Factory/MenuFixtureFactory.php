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
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuFixtureFactory extends AbstractExampleFactory implements MenuFixtureFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $menuFactory;

    /**
     * @var OptionsResolver
     */
    private $optionResolver;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    public function __construct(FactoryInterface $menuFactory, SlugGeneratorInterface $slugGenerator)
    {
        $this->menuFactory = $menuFactory;
        $this->slugGenerator = $slugGenerator;
        $this->optionResolver = new OptionsResolver();
        $this->configureOptions($this->optionResolver);
        $this->faker = \Faker\Factory::create();
    }

    public function create(array $options = []): MenuInterface
    {
        $options = $this->optionResolver->resolve($options);

        /** @var MenuInterface $menu */
        $menu = $this->menuFactory->createNew();
        $menu->setIsSystem($options['isSystem']);
        $menu->setCreatedAt($options['createdAt']);
        $menu->setUpdatedAt($options['updatedAt']);
        $menu->setCode($options['code']);

        return $menu;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('isSystem', function(Options $options): bool {
                return $this->faker->boolean(80);
            })
            ->setDefault('createdAt', function(Options $options): \DateTimeImmutable {
                return \DateTimeImmutable::createFromMutable($this->faker->dateTime());
            })
            ->setDefault('updatedAt', function(Options $options): \DateTimeImmutable {
                return \DateTimeImmutable::createFromMutable($this->faker->dateTime());
            })
            ->setDefault('code', function(Options $options): string {
                return $this->slugGenerator->generate($this->faker->sentence(2, true));
            })
        ;
    }
}
