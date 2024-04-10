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

namespace MonsieurBiz\SyliusMenuPlugin\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UrlType extends TextType
{
    public function getBlockPrefix(): string
    {
        return 'monsieurbiz_sylius_menu_url';
    }
}
