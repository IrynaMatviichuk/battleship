<?php

namespace Battleship\Domain;

class Coordinate
{
    private int $row;
    private int $column;

    public function __construct(int $row, int $column)
    {
        if (!$this->isWithinRange($row) || !$this->isWithinRange($column)) {
            throw new InvalidCoordinateRangeException();
        }

        $this->row = $row;
        $this->column = $column;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function matches(Coordinate $coordinate): bool
    {
        return $this->row === $coordinate->row && $this->column === $coordinate->column;
    }

    private function isWithinRange(int $value): bool
    {
        return $value >= 0 && $value < Board::DEFAULT_SIZE;
    }
}
