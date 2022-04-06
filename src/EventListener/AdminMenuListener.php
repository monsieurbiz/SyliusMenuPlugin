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

namespace MonsieurBiz\SyliusMenuPlugin\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        if (null !== $content = $menu->getChild('configuration')) {
            $content->addChild('app-menu', ['route' => 'monsieurbiz_menu_admin_menu_index'])
                ->setLabel('monsieurbiz_menu.ui.menus')
                ->setLabelAttribute('icon', 'sitemap')
            ;
        }
    }
}
