<?php

declare(strict_types=1);

namespace App\OutputDTO;

class Todo
{
    /** @var int */
    public $id;

    /** @var bool */
    public $completed;

    /** @var string */
    public $text;

    public function __construct(int $id, bool $completed, string $text)
    {
        $this->id = $id;
        $this->completed = $completed;
        $this->text = $text;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }
}
