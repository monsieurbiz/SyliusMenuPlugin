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

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface MenuRepositoryInterface extends RepositoryInterface
{
    public function findOneByLocaleAndCode(string $localeCode, string $code): ?MenuInterface;
}
