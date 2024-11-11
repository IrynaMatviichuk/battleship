<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;

class Cell
{
    use EventRecorder;

    public readonly int $id;
    private ?int $shipId = null;
    private Coordinate $coordinate;
    private ?bool $guessed = null;

    public function __construct(int $id, Coordinate $coordinate)
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
