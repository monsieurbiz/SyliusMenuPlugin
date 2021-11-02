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

namespace MonsieurBiz\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface MenuItemTranslationInterface extends ResourceInterface, TranslationInterface
{
    public function getLabel(): ?string;

    public function setLabel(?string $label): void;

    public function getUrl(): ?string;

    public function setUrl(?string $url): void;
}
