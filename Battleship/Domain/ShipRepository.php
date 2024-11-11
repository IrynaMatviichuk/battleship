<?php

namespace Battleship\Domain;

interface ShipRepository
{
    public function findById(int $shipId): Ship;
}
