<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\TodoRepository")]
#[ORM\Table(name: "todos")]
class Todo
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;


    #[ORM\Column(type: "boolean")]
    private bool $completed;

    #[ORM\Column(type: "string")]
    private string $text;

    public function __construct(string $text, bool $completed = false)
    {
        $this->completed = $completed;
        $this->text = $text;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
