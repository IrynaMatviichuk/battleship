<?php

namespace Battleship\Domain;

interface CellRepository
{
    public function findById(string $cellId): Cell;

    public function add(Cell $cell): void;
}
