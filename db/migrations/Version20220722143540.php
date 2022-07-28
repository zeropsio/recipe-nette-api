<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722143540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create todos tables for PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf(
            !($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform),
            'Migration can only be executed safely on \'postgresql\'.'
        );
        $this->addSql(
            'CREATE TABLE todos (id SERIAL NOT NULL, completed BOOLEAN NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id));'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE todos');
    }
}
