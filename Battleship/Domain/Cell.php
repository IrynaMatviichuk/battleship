<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Cell
{
    use EventRecorder;

    #[Id, Column(type: 'string', unique: true)]
    public readonly string $id;

    #[ManyToOne(targetEntity: Ship::class, inversedBy: 'cells')]
    private ?Ship $ship = null;

    #[Column(type: 'coordinate')]
    private Coordinate $coordinate;

    #[Column(type: 'boolean', nullable: true)]
    private ?bool $guessed = null;

    #[ManyToOne(targetEntity: Board::class, inversedBy: 'cells')]
    private Board $board;

    public function __construct(string $id, Board $board, Coordinate $coordinate)
    {
        $this->id = $id;
        $this->board = $board;
        $this->coordinate = $coordinate;
    }

    public function hasCoordinate(Coordinate $coordinate): bool
    {
        return $coordinate->matches($this->coordinate);
    }

    public function guess(): bool
    {
        if ($this->guessed !== null) {
            throw new \InvalidArgumentException();
        }

        $this->guessed = $this->ship !== null;

        return $this->guessed;
    }

    public function occupy(Ship $ship): void
    {
        if ($this->ship !== null) {
            throw new \InvalidArgumentException();
        }

        $this->ship = $ship;
    }

    public function isGuessed(): ?bool
    {
        return $this->guessed;
    }

    public function getShip(): ?Ship
    {
        return $this->ship;
    }
}
