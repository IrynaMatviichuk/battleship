<?php

namespace Battleship\Application;

readonly class StartGame
{
    public function __construct(public string $boardId) {}
}