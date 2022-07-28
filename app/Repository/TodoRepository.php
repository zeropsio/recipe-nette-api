<?php

declare(strict_types=1);

namespace App\Repository;


use App\Entity\Todo;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;

/**
 * @extends EntityRepository<Todo>
 */
class TodoRepository extends EntityRepository
{
    public function created(string $text): Todo
    {
        $todo = new Todo($text);

        $this->_em->persist($todo);
        $this->_em->flush();

        return $todo;
    }
}
