<?php

namespace Battleship\Domain;

interface BoardRepository
{
    public function findById(int $boardId): Board;
}
