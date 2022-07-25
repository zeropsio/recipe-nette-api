<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722143540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Todos';
    }

    public function up(Schema $schema): void
    {
        $sqlInit = json_decode(\getenv('ZEROPS_RECIPE_DATA_SEED') ?: '["new todo (1)"]', true);

        if ($sqlInit === false) {
            throw new \InvalidArgumentException('invalid input data - sql init');
        }

        foreach ($sqlInit as $item) {
            $this->addSql("INSERT INTO `todos` (`completed`, `text`) VALUES (?, ?)", [0, $item]);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE `todos`');
    }
}
