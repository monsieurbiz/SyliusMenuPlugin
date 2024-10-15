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

namespace MonsieurBiz\SyliusMenuPlugin\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\Menu;
use MonsieurBiz\SyliusMenuPlugin\Hydrator\MenuTreeHydrator;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository implements MenuRepositoryInterface
{
    public function findOneByLocaleAndCode(string $localeCode, string $code): ?MenuInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->addSelect('item')
            ->addSelect('item_translation')
            ->innerJoin('o.items', 'item')
            ->innerJoin('item.translations', 'item_translation', 'WITH', 'item_translation.locale = :locale')
            ->where('o.code = :code')
            ->setParameter('locale', $localeCode)
            ->setParameter('code', $code)
        ;

        /** @phpstan-ignore-next-line */
        return (new MenuTreeHydrator())($queryBuilder->getQuery()->getOneOrNullResult());
    }
}
