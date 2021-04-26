<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Menu implements MenuInterface
{
    use TimestampableTrait;

    /**
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @var string|null
     */
    private ?string $code = null;

    /**
     * @var Collection|null
     */
    private ?Collection $items;

    /**
     * @var bool|null
     */
    private ?bool $isSystem = false;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): ?Collection
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstLevelItems(): array
    {
        $items = $this->getItems();
        if (null === $items) {
            return [];
        }
        $filteredItems = $items->filter(function($item) {
            if (!$item->getParent()) {
                return $item;
            }

            return null;
        })->toArray();
        uasort($filteredItems, function($itemA, $itemB) {
            return $itemA->getPosition() <=> $itemB->getPosition();
        });

        return array_values($filteredItems);
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem(MenuItemInterface $item): bool
    {
        if (null === $this->items) {
            return false;
        }

        return $this->items->contains($item);
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(MenuItemInterface $item): void
    {
        if (null !== $this->items && !$this->hasItem($item)) {
            $this->items->add($item);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(MenuItemInterface $item): void
    {
        if (null !== $this->items && $this->hasItem($item)) {
            $this->items->removeElement($item);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSystem(): ?bool
    {
        return $this->isSystem;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSystem(?bool $isSystem): void
    {
        $this->isSystem = $isSystem;
    }
}
