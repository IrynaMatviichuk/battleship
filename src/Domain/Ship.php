<?php

namespace Battleship\Domain;

class Ship
{
    private int $size;

    private array $coordinates;

    public function __construct(int $size)
    {
        $this->size = $size;
    }

    public function place(array $coordinates): void
    {
        if (count($coordinates) !== $this->size) {
            throw new \InvalidArgumentException();
        }

        $this->coordinates = $coordinates;
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }
}
