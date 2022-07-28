<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722142137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Todos table for MariaDB';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf(
            !($this->connection->getDatabasePlatform() instanceof MariaDBPlatform),
            'Migration can only be executed safely on \'mariadb\'.'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `todos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `completed` tinyint(1) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `todos`');
    }
}
