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

class BrowsableObjectProvider implements BrowsableObjectProviderInterface
{
    public function __construct(private iterable $urlProviders)
    {
    }

    public function getUrlProviders(): array
    {
        $urlProviders = [];
        foreach ($this->urlProviders as $urlProvider) {
            $urlProviders[$urlProvider->getCode()] = $urlProvider;
        }

        uasort($urlProviders, fn ($urlProviderA, $urlProviderB) => $urlProviderB->getPriority() <=> $urlProviderA->getPriority());

        return $urlProviders;
    }
}
