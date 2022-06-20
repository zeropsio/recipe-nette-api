<?php

declare(strict_types=1);

namespace App\OutputDTO;

class TodoList
{
    /** @var array|Todo[] */
    public $list = [];

    public function __construct(array $todos)
    {
        $this->list = (function(Todo ...$list): array {
            return $list;
        })(...$todos);
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function setList(array $list): void
    {
        $this->list = $list;
    }
}
