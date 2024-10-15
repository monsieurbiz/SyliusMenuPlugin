<?php

/**
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\DataProvider\Tree;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\Menu;

class MenuItemTreeProvider
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleContextInterface $localeContext,
        private readonly TranslationLocaleProviderInterface $translationLocaleProvider,
    ) {
    }

    public function getArrayResult(Menu $menu): array
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
            ->setParameter('fallbackLocale', $fallbackLocale, Types::STRING);

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}
