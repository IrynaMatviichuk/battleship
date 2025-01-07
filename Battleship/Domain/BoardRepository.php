<?php

namespace Battleship\Domain;

interface BoardRepository
{
    public function findById(string $boardId): Board;

    public function add(Board $board): void;
}
