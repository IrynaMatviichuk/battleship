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

    #[Column(type: 'string', nullable: true)]
    private ?string $shipId = null;

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

        $this->guessed = $this->shipId !== null;

        return $this->guessed;
    }

    public function occupy(string $shipId): void
    {
        if ($this->shipId !== null) {
            throw new \InvalidArgumentException('Cell is already occupied');
        }

        $this->shipId = $shipId;
    }

    public function isGuessed(): ?bool
    {
        return $this->guessed;
    }

    public function getShip(): ?string
    {
        return $this->shipId;
    }
}
