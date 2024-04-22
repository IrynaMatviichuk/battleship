<?php

namespace Battleship\Domain;

class Board
{
    private const SIZE = 10;

    private \SplFixedArray $cells;

    public function __construct()
    {
        $this->cells = new \SplFixedArray(self::SIZE);

        foreach (range(0, self::SIZE - 1) as $row)
        {
            $this->cells[$row] = new \SplFixedArray(self::SIZE);

            foreach (range(0, self::SIZE - 1) as $column)
            {
                $this->cells[$row][$column] = new Cell();
            }
        }
    }

    public function guess(int $row, int $column): void
    {
        $cell = $this->cells[$row][$column];

        $this->cells[$row][$column] = $cell->guess();
    }

    public function getCell(int $row, int $column): Cell
    {
        return $this->cells[$row][$column];
    }

    public function placeShip(Ship $ship): void
    {
        foreach ($ship->getCoordinates() as $coordinate) {
            $cell = $this->getCell($coordinate[0], $coordinate[1]);

            $this->cells[$coordinate[0]][$coordinate[1]] = $cell->occupy();
        }
    }
}
