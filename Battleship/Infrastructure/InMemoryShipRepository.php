<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Ship;
use Battleship\Domain\ShipRepository;

class InMemoryShipRepository implements ShipRepository
{
    public function __construct(private array $ships) {
        foreach ($this->ships as $ship) {
            $this->ships[$ship->id] = $ship;
        }
    }

    public function findById(string $shipId): Ship
    {
        return $this->ships[$shipId];
    }
}
