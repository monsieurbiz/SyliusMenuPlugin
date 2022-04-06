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

namespace MonsieurBiz\SyliusMenuPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class MenuItemType extends AbstractResourceType
{
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $item = $event->getData();
                $event->getForm()
                    ->add('parent', EntityType::class, [
                        'class' => $this->dataClass,
                        'required' => false,
                        'choice_label' => 'translation.label',
                        'choice_value' => 'id',
                        'query_builder' => function (EntityRepository $er) use ($item) {
                            $qb = $er->createQueryBuilder('o');
                            $qb
                                ->where('o.menu = :menu')
                                ->setParameter('menu', $item->getMenu())
                            ;
                            if (null !== $item && null !== $item->getId()) {
                                $qb
                                    ->andWhere('o != :currentItem')
                                    ->setParameter('currentItem', $item)
                                ;
                            }

                            return $qb;
                        },
                    ])
                    ->add('translations', ResourceTranslationsType::class, [
                        'label' => 'sylius.ui.translations',
                        'entry_type' => MenuItemTranslationType::class,
                    ])
                ;
            })
        ;
    }
}
