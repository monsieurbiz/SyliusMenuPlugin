<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Repository;

use Doctrine\ORM\Query\Expr;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuItemInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class MenuItemRepository extends EntityRepository implements MenuItemRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLastPositionWithinMenu(MenuInterface $menu): int
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->resetDQLPart('select')
            ->select((new Expr())->max('o.position'))
            ->where('o.menu = :menu')
            ->andWhere('o.parent IS NULL')
            ->setParameter('menu', $menu)
        ;

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPositionWithinMenuItem(MenuItemInterface $menuItem): int
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->resetDQLPart('select')
            ->select((new Expr())->max('o.position'))
            ->where('o.parent = :parent')
            ->setParameter('parent', $menuItem)
        ;

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
