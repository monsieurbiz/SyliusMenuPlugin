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

use Symfony\Component\Routing\RouterInterface;

abstract class AbstractUrlProvider implements UrlProviderInterface
{
    protected string $code;

    protected string $icon = 'angle right';

    protected int $priority = 0;

    public function __construct(
        protected RouterInterface $router
    ) {
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    abstract public function getItems(string $locale): array;
}
