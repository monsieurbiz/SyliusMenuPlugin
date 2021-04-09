<?php

/*
 * This file is part of Monsieur Biz' menu plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407172059 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monsieurbiz_menu_item_menu (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', position INT NOT NULL, highlighted TINYINT(1) DEFAULT NULL, INDEX IDX_C95D00BECCD7E912 (menu_id), INDEX IDX_C95D00BE727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_menu_item_translation_menu (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL, label VARCHAR(255) NOT NULL, headline VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_menu_menu (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', code VARCHAR(255) DEFAULT NULL, is_system TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monsieurbiz_menu_item_menu ADD CONSTRAINT FK_C95D00BECCD7E912 FOREIGN KEY (menu_id) REFERENCES monsieurbiz_menu_menu (id)');
        $this->addSql('ALTER TABLE monsieurbiz_menu_item_menu ADD CONSTRAINT FK_C95D00BE727ACA70 FOREIGN KEY (parent_id) REFERENCES monsieurbiz_menu_item_menu (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_menu_item_menu DROP FOREIGN KEY FK_C95D00BE727ACA70');
        $this->addSql('ALTER TABLE monsieurbiz_menu_item_menu DROP FOREIGN KEY FK_C95D00BECCD7E912');
        $this->addSql('DROP TABLE monsieurbiz_menu_item_menu');
        $this->addSql('DROP TABLE monsieurbiz_menu_item_translation_menu');
        $this->addSql('DROP TABLE monsieurbiz_menu_menu');
    }
}
