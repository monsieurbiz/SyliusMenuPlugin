<?php

/*
 * This file is part of SyliusMenuPlugin website.
 *
 * (c) SyliusMenuPlugin <sylius+syliusmenuplugin@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\DataProvider\Tree;

use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;

interface MenuItemTreeProviderInterface
{
    public function getArrayResult(MenuInterface $menu): array;
}
