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

namespace MonsieurBiz\SyliusMenuPlugin\Tests\Unit\DataProvider\Tree;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\Deprecations\Deprecation;
use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusMenuPlugin\DataProvider\Tree\MenuItemTreeProvider;
use MonsieurBiz\SyliusMenuPlugin\Entity\MenuInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class MenuItemTreeProviderTest extends TestCase
{
    public function testItReturnsTheMenuItemsWithTheirTranslatedLabel(): void
    {
        $result = $this->createProvider()->getArrayResult($this->createMenu());

        self::assertSame([
            ['id' => 2, 'parent_id' => 1, 'menu_id' => 1, 'name' => 'Enfant', 'enabled' => 1],
            ['id' => 3, 'parent_id' => 1, 'menu_id' => 1, 'name' => 'Fallback only', 'enabled' => 0],
            ['id' => 1, 'parent_id' => null, 'menu_id' => 1, 'name' => 'Parent', 'enabled' => 1],
        ], array_map(static fn (array $row): array => array_map(
            static fn (mixed $value): mixed => is_numeric($value) ? (int) $value : $value,
            $row,
        ), $result));
    }

    /**
     * Passing an array to `QueryBuilder::select()` is deprecated in DBAL 3 and fails with DBAL 4.
     */
    public function testItDoesNotPassAnArrayToTheQueryBuilderSelect(): void
    {
        $provider = $this->createProvider();
        $menu = $this->createMenu();

        Deprecation::enableTrackingDeprecations();
        $before = Deprecation::getTriggeredDeprecations()['https://github.com/doctrine/dbal/issues/3837'] ?? 0;

        $provider->getArrayResult($menu);

        self::assertSame($before, Deprecation::getTriggeredDeprecations()['https://github.com/doctrine/dbal/issues/3837'] ?? 0);
    }

    private function createProvider(): MenuItemTreeProvider
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getConnection')->willReturn($this->createConnection());

        $localeContext = $this->createMock(LocaleContextInterface::class);
        $localeContext->method('getLocaleCode')->willReturn('fr_FR');

        $translationLocaleProvider = $this->createMock(TranslationLocaleProviderInterface::class);
        $translationLocaleProvider->method('getDefaultLocaleCode')->willReturn('en_US');

        return new MenuItemTreeProvider($entityManager, $localeContext, $translationLocaleProvider);
    }

    private function createMenu(): MenuInterface
    {
        $menu = $this->createMock(MenuInterface::class);
        $menu->method('getId')->willReturn(1);

        return $menu;
    }

    private function createConnection(): Connection
    {
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
        $connection->executeStatement('CREATE TABLE monsieurbiz_menu (id INTEGER PRIMARY KEY)');
        $connection->executeStatement('CREATE TABLE monsieurbiz_menu_item (id INTEGER PRIMARY KEY, menu_id INTEGER, parent_id INTEGER NULL, position INTEGER, enabled INTEGER)');
        $connection->executeStatement('CREATE TABLE monsieurbiz_menu_item_translation (id INTEGER PRIMARY KEY, translatable_id INTEGER, locale VARCHAR(255), label VARCHAR(255))');

        $connection->insert('monsieurbiz_menu', ['id' => 1]);
        $connection->insert('monsieurbiz_menu', ['id' => 2]);
        $connection->insert('monsieurbiz_menu_item', ['id' => 1, 'menu_id' => 1, 'parent_id' => null, 'position' => 0, 'enabled' => 1]);
        $connection->insert('monsieurbiz_menu_item', ['id' => 2, 'menu_id' => 1, 'parent_id' => 1, 'position' => 0, 'enabled' => 1]);
        $connection->insert('monsieurbiz_menu_item', ['id' => 3, 'menu_id' => 1, 'parent_id' => 1, 'position' => 1, 'enabled' => 0]);
        $connection->insert('monsieurbiz_menu_item', ['id' => 4, 'menu_id' => 2, 'parent_id' => null, 'position' => 0, 'enabled' => 1]);
        $connection->insert('monsieurbiz_menu_item_translation', ['translatable_id' => 1, 'locale' => 'en_US', 'label' => 'Parent']);
        $connection->insert('monsieurbiz_menu_item_translation', ['translatable_id' => 2, 'locale' => 'en_US', 'label' => 'Child']);
        $connection->insert('monsieurbiz_menu_item_translation', ['translatable_id' => 2, 'locale' => 'fr_FR', 'label' => 'Enfant']);
        $connection->insert('monsieurbiz_menu_item_translation', ['translatable_id' => 3, 'locale' => 'en_US', 'label' => 'Fallback only']);
        $connection->insert('monsieurbiz_menu_item_translation', ['translatable_id' => 4, 'locale' => 'en_US', 'label' => 'Other menu']);

        return $connection;
    }
}
