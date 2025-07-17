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

namespace MonsieurBiz\SyliusMenuPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250717143849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_menu_item ADD enabled TINYINT(1) DEFAULT 1 NOT NULL, CHANGE target_blank target_blank TINYINT(1) DEFAULT 0 NOT NULL, CHANGE noreferrer noreferrer TINYINT(1) DEFAULT 0 NOT NULL, CHANGE noopener noopener TINYINT(1) DEFAULT 0 NOT NULL, CHANGE nofollow nofollow TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_menu_item DROP enabled, CHANGE target_blank target_blank TINYINT(1) NOT NULL, CHANGE noreferrer noreferrer TINYINT(1) NOT NULL, CHANGE noopener noopener TINYINT(1) NOT NULL, CHANGE nofollow nofollow TINYINT(1) NOT NULL');
    }
}
