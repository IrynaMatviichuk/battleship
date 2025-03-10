<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Cell
{
    use EventRecorder;

    #[Id, Column(type: 'string', unique: true)]
    public readonly string $id;

//    #[ManyToOne(targetEntity: 'Ship', cascade: ['all'], fetch: 'EAGER')]
//    private ?Ship $ship;

    private ?int $shipId = null;

    #[Column(type: 'coordinate')]
    private Coordinate $coordinate;

    #[Column(type: 'boolean', nullable: true)]
    private ?bool $guessed = null;

    public function __construct(string $id, Coordinate $coordinate)
    {
        $this->id = $id;
        $this->coordinate = $coordinate;
    }

    public function guess(): bool
    {
        if ($this->guessed !== null) {
            throw new \InvalidArgumentException();
        }

        $this->guessed = $this->shipId !== null;

        return $this->guessed;
    }

    public function occupy(int $shipId): void
    {
        if ($this->shipId !== null) {
            throw new \InvalidArgumentException();
        }

        $this->shipId = $shipId;
    }

    public function isGuessed(): ?bool
    {
        return $this->guessed;
    }

    public function getShipId(): ?int
    {
        return $this->shipId;
    }
}
