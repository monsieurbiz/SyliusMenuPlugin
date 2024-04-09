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

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductUrlProvider extends AbstractUrlProvider
{
    public const PROVIDER_CODE = 'product';

    protected string $code = self::PROVIDER_CODE;

    protected string $icon = 'cube';

    protected int $priority = 50;

    public function __construct(
        RouterInterface $router,
        private ProductRepositoryInterface $productRepository,
    ) {
        parent::__construct($router);
    }

    public function getItems(string $locale): array
    {
        $products = $this->productRepository->createListQueryBuilder($locale)
            ->andWhere('o.enabled = :enabled')
            ->setParameter('enabled', true)
            ->getQuery()
            ->getResult()
        ;

        $items = [];
        /** @var ProductInterface $product */
        foreach ($products as $product) {
            $items[] = [
                'name' => $product->getName(),
                'value' => $this->router->generate('sylius_shop_product_show', ['slug' => $product->getSlug(), '_locale' => $locale]),
            ];
        }

        usort($items, fn ($itemA, $itemB) => $itemA['name'] <=> $itemB['name']);

        return $items;
    }
}
