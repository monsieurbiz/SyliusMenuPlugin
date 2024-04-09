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

use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

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

    protected function getResults(string $locale): iterable
    {
        return $this->productRepository->createListQueryBuilder($locale)
            ->andWhere('o.enabled = :enabled')
            ->setParameter('enabled', true)
            ->getQuery()
            ->getResult()
        ;
    }

    protected function addItemFromResult(object $result, string $locale): void
    {
        Assert::isInstanceOf($result, Product::class);
        $this->addItem(
            (string) $result->getName(),
            $this->router->generate('sylius_shop_product_show', ['slug' => $result->getSlug(), '_locale' => $locale])
        );
    }
}
