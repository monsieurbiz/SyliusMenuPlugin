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
use Sylius\Component\Resource\Model\TranslatableInterface;

interface MenuItemInterface extends ResourceInterface, TranslatableInterface
{
    public function getMenu(): ?MenuInterface;

    public function setMenu(?MenuInterface $menu): void;

    /**
     * @return MenuItemInterface|null
     */
    public function getParent(): ?self;

    /**
     * @param MenuItemInterface|null $parent
     */
    public function setParent(?self $parent): void;

    public function getPosition(): ?int;

    public function setPosition(?int $position): void;

    /**
     * @return Collection<int, MenuItemInterface>|null
     */
    public function getItems(): ?Collection;

    /**
     * @param Collection<int, MenuItemInterface>|null $items
     */
    public function setItems(?Collection $items): void;

    /**
     * @param MenuItemInterface $item
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

    public function getLabel(): ?string;

    public function getUrl(): ?string;
}
