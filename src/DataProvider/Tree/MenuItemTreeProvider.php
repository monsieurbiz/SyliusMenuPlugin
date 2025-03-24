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

namespace MonsieurBiz\SyliusMenuPlugin\DataProvider\Tree;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class MenuItemTreeProvider implements MenuItemTreeProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleContextInterface $localeContext,
        private readonly TranslationLocaleProviderInterface $translationLocaleProvider,
    ) {
    }

    public function getArrayResult(MenuInterface $menu): array
    {
        $fallbackLocale = $this->translationLocaleProvider->getDefaultLocaleCode();

        try {
            $currentLocale = $this->localeContext->getLocaleCode();
        } catch (LocaleNotFoundException) {
            $currentLocale = $fallbackLocale;
        }

        $queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select([
                'mni.id as id',
                'mni.parent_id as parent_id',
                'mm.id as menu_id',
                'COALESCE(current_translation.label, fallback_translation.label) as name',
            ])
            ->from('monsieurbiz_menu_item', 'mni')
            ->join(
                'mni',
                'monsieurbiz_menu',
                'mm',
                'mni.menu_id = mm.id'
            )
            ->leftJoin(
                'mni',
                'monsieurbiz_menu_item_translation',
                'current_translation',
                (string) $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('current_translation.translatable_id', 'mni.id'),
                    $queryBuilder->expr()->eq('current_translation.locale', ':currentLocale'),
                ),
            )
            ->leftJoin(
                'mni',
                'monsieurbiz_menu_item_translation',
                'fallback_translation',
                (string) $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('fallback_translation.translatable_id', 'mni.id'),
                    $queryBuilder->expr()->eq('fallback_translation.locale', ':fallbackLocale'),
                ),
            )
            ->where('mni.menu_id = :menu')
            ->orderBy('mni.parent_id', Criteria::DESC)
            ->addOrderBy('mni.position', Criteria::ASC)
            ->setParameter('menu', $menu->getId(), Types::INTEGER)
            ->setParameter('currentLocale', $currentLocale, Types::STRING)
            ->setParameter('fallbackLocale', $fallbackLocale, Types::STRING)
        ;

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}
