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

namespace MonsieurBiz\SyliusMenuPlugin\Provider;

use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Routing\RouterInterface;

class TaxonUrlProvider extends AbstractUrlProvider
{
    public const PROVIDER_CODE = 'taxon';

    protected string $code = self::PROVIDER_CODE;

    protected string $icon = 'folder';

    protected int $priority = 100;

    public function __construct(
        RouterInterface $router,
        private TaxonRepositoryInterface $taxonRepository,
    ) {
        parent::__construct($router);
    }

    public function getItems(string $locale): array
    {
        $taxons = $this->taxonRepository->createListQueryBuilder()
            ->andWhere('translation.locale = :locale')
            ->andWhere('o.enabled = :enabled')
            ->setParameter('locale', $locale)
            ->setParameter('enabled', true)
            ->getQuery()
            ->getResult()
        ;

        $items = [];
        /** @var TaxonInterface $taxon */
        foreach ($taxons as $taxon) {
            if ($taxon->isRoot()) {
                continue;
            }
            $items[] = [
                'name' => $this->getTaxonFullName($taxon),
                'value' => $this->router->generate('sylius_shop_product_index', ['slug' => $taxon->getSlug(), '_locale' => $locale]),
            ];
        }

        usort($items, fn ($itemA, $itemB) => $itemA['name'] <=> $itemB['name']);

        return $items;
    }

    private function getTaxonFullName(TaxonInterface $taxon): string
    {
        $fullName = (string) $taxon->getName();
        $parent = $taxon->getParent();
        while (null !== $parent) {
            /** @var TaxonInterface $parent */
            if ($parent->isRoot()) {
                break;
            }
            $fullName = $parent->getName() . ' > ' . $fullName;
            $parent = $parent->getParent();
        }

        return $fullName;
    }
}
