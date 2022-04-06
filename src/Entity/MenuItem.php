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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * @method MenuItemTranslationInterface getTranslation(?string $locale = null)
 */
class MenuItem implements MenuItemInterface
{
    use TimestampableTrait;

    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    protected ?int $id = null;

    protected ?MenuInterface $menu = null;

    /**
     * @var Collection<int, MenuItemInterface>|null
     */
    protected ?Collection $items = null;

    protected ?MenuItemInterface $parent = null;

    protected ?int $position = null;

    /**
     * MenuItem constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->items = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getMenu(): ?MenuInterface
    {
        return $this->menu;
    }

    /**
     * @inheritdoc
     */
    public function setMenu(?MenuInterface $menu): void
    {
        $this->menu = $menu;
        if (null !== $menu) {
            $menu->addItem($this);
        }
    }

    /**
     * @inheritdoc
     */
    public function getParent(): ?MenuItemInterface
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function setParent(?MenuItemInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @inheritdoc
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @inheritdoc
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @inheritdoc
     */
    public function getItems(): ?Collection
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function setItems(?Collection $items): void
    {
        $this->items = $items;
    }

    /**
     * @inheritdoc
     */
    public function hasItem(MenuItemInterface $item): bool
    {
        if (null === $this->items) {
            return false;
        }

        return $this->items->contains($item);
    }

    /**
     * @inheritdoc
     */
    public function addItem(MenuItemInterface $item): void
    {
        if (null !== $this->items && !$this->hasItem($item)) {
            $this->items->add($item);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeItem(MenuItemInterface $item): void
    {
        if (null !== $this->items && $this->hasItem($item)) {
            $this->items->removeElement($item);
        }
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): ?string
    {
        return $this->getTranslation()->getLabel();
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): ?string
    {
        return $this->getTranslation()->getUrl();
    }

    /**
     * @inheritdoc
     */
    protected function createTranslation(): MenuItemTranslationInterface
    {
        return new MenuItemTranslation();
    }
}
