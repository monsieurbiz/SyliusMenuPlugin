<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\AbstractTranslation;

class MenuItemTranslation extends AbstractTranslation implements MenuItemTranslationInterface
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * @var string|null
     */
    protected ?string $label = null;

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
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }
}
