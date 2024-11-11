<?php

namespace Battleship\Domain;

interface CellRepository
{
    public function findById(int $cellId): Board;
}
