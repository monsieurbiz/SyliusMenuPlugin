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
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void;

    /**
     * @return Collection<int, MenuItemInterface>|null
     */
    public function getItems(): ?Collection;

    /**
     * @return array
     */
    public function getFirstLevelItems(): array;

    /**
     * @param MenuItemInterface $item
     *
     * @return bool
     */
    public function hasItem(MenuItemInterface $item): bool;

    /**
     * @param MenuItemInterface $item
     */
    public function addItem(MenuItemInterface $item): void;

    /**
     * @param MenuItemInterface $item
     */
    public function removeItem(MenuItemInterface $item): void;

    /**
     * @return bool|null
     */
    public function isSystem(): ?bool;

    /**
     * @param bool|null $isSystem
     */
    public function setIsSystem(?bool $isSystem): void;
}
