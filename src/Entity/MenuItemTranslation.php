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

use Sylius\Component\Resource\Model\AbstractTranslation;

class MenuItemTranslation extends AbstractTranslation implements MenuItemTranslationInterface
{
    protected ?int $id = null;

    protected ?string $url = null;

    protected ?string $label = null;

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
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @inheritdoc
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }
}
