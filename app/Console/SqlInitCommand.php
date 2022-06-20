<?php

declare(strict_types=1);

namespace App\Console;

use App\Repository\TodoRepository;
use Nette\Database\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SqlInitCommand extends Command
{
    /** @var Connection */
    private $connection;
    /** @var TodoRepository */
    private $todoRepository;

    public function __construct(Connection $connection, TodoRepository $todoRepository)
    {
        parent::__construct();
        $this->connection = $connection;
        $this->todoRepository = $todoRepository;
    }

    protected function configure(): void
    {
        $this->setName('app:sql-init')
            ->setDescription('Create database table and fill data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $sqlInit = json_decode(\getenv('ZEROPS_RECIPE_DATA_SEED') ?: '["new todo (1)"]', true);

            if ($sqlInit === false) {
                throw new \InvalidArgumentException('invalid input data - sql init');
            }

            $this->connection->query('
CREATE TABLE IF NOT EXISTS `todos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `completed` tinyint(1) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
');

            foreach ($sqlInit as $item) {
                $this->todoRepository->created($item);
            }
        } catch (\Throwable $e) {
            $output->writeLn('<error>' . $e->getMessage() . '</error>');
            return 1; // non-zero return code means error
        }

        return 0; // zero return code means everything is ok
    }
}
