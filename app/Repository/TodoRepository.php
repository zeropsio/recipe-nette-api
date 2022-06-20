<?php

declare(strict_types=1);

namespace App\Repository;

use Nette\Database\Connection;

class TodoRepository
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        return $this->db->fetchAll('SELECT `id`, `completed`, `text` FROM `todos`');
    }

    public function find(int $id)
    {
        return $this->db->fetch('SELECT `id`, `completed`, `text` FROM `todos` WHERE `id` = ?', $id);
    }

    public function created(string $text)
    {
        $this->db->query('INSERT INTO `todos` ', [
            'completed' => false,
            'text' => $text,
        ]);

        return (int)$this->db->getInsertId();
    }

    public function patch(int $id, array $patch)
    {
        $this->db->query('UPDATE `todos` SET ', $patch, ' WHERE `id` = ?', $id);

        return (int)$this->db->getInsertId();
    }

    public function delete(int $id)
    {
        $this->db->query('DELETE FROM `todos` WHERE `id` = ?', $id);
    }
}
