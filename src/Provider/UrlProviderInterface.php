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

interface UrlProviderInterface
{
    public function getIcon(): string;

    public function getCode(): string;

    public function getPriority(): int;

    public function getItems(string $locale, string $search = ''): array;
}
