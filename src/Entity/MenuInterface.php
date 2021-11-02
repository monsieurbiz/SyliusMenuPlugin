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

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface MenuInterface extends ResourceInterface, TimestampableInterface
{
    public function getId(): ?int;

    public function getCode(): ?string;

    public function setCode(?string $code): void;

    /**
     * @return Collection<int, MenuItemInterface>|null
     */
    public function getItems(): ?Collection;

    public function getFirstLevelItems(): array;

    public function hasItem(MenuItemInterface $item): bool;

    public function addItem(MenuItemInterface $item): void;

    public function removeItem(MenuItemInterface $item): void;

    public function isSystem(): ?bool;

    public function setIsSystem(?bool $isSystem): void;
}
