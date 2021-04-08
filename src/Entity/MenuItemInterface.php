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
use Sylius\Component\Resource\Model\TranslatableInterface;

interface MenuItemInterface extends ResourceInterface, TranslatableInterface
{
    /**
     * @return MenuInterface|null
     */
    public function getMenu(): ?MenuInterface;

    /**
     * @param MenuInterface|null $menu
     */
    public function setMenu(?MenuInterface $menu): void;

    /**
     * @return MenuItemInterface|null
     */
    public function getParent(): ?self;

    /**
     * @param MenuItemInterface|null $parent
     */
    public function setParent(?self $parent): void;

    /**
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void;

    /**
     * @return array|null
     */
    public function getItems(): ?array;

    /**
     * @param MenuItemInterface $item
     *
     * @return bool
     */
    public function hasItem(self $item): bool;

    /**
     * @param MenuItemInterface $item
     */
    public function addItem(self $item): void;

    /**
     * @param MenuItemInterface $item
     */
    public function removeItem(self $item): void;

    /**
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * @return string|null
     */
    public function getHeadline(): ?string;

    /**
     * @return bool|null
     */
    public function isHighlighted(): ?bool;

    /**
     * @param bool|null $highlighted
     */
    public function setHighlighted(?bool $highlighted): void;
}
