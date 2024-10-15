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
use Webmozart\Assert\Assert;

class TaxonUrlProvider extends AbstractUrlProvider
{
    public const PROVIDER_CODE = 'taxon';

    protected string $code = self::PROVIDER_CODE;

    protected string $icon = 'world';

    protected int $priority = 100;

    public function __construct(
        RouterInterface $router,
        private TaxonRepositoryInterface $taxonRepository,
    ) {
        parent::__construct($router);
    }

    protected function getResults(string $locale, string $search = ''): iterable
    {
        $queryBuilder = $this->taxonRepository->createListQueryBuilder()
            ->andWhere('translation.locale = :locale')
            ->andWhere('o.enabled = :enabled')
            ->andWhere('o.parent IS NOT NULL') // Avoid root taxons
            ->setParameter('locale', $locale)
            ->setParameter('enabled', true)
        ;

        if (!empty($search)) {
            $queryBuilder
                ->andWhere('translation.name LIKE :search OR o.code LIKE :search OR translation.slug LIKE :search')
                ->setParameter('search', '%' . $search . '%')
            ;
        }

        $queryBuilder->setMaxResults($this->getMaxResults());

        /** @phpstan-ignore-next-line */
        return $queryBuilder->getQuery()->getResult();
    }

    protected function addItemFromResult(object $result, string $locale): void
    {
        Assert::isInstanceOf($result, TaxonInterface::class);
        /** @var TaxonInterface $result */
        $result->setCurrentLocale($locale);

        // Avoid incorrect locale while getting fullname in the root taxon
        if (!$result->isRoot()) {
            $result->getRoot()?->setCurrentLocale($locale);
        }

        $this->addItem(
            (string) $result->getFullname(' > '),
            $this->router->generate('sylius_shop_product_index', ['slug' => $result->getSlug(), '_locale' => $locale])
        );
    }
}
