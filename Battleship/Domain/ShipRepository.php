<?php

namespace Battleship\Domain;

interface ShipRepository
{
    public function findById(string $shipId): Ship;
}
