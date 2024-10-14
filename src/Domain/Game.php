<?php

namespace Battleship\Domain;

class Game
{
    private array $boards;

    public function __construct(array $boards, array $players)
    {
        $this->boards = $boards;
    }

    public static function startGame(): self
    {
        $players = [
            1 => new Player(1),
            2 => new Player(2),
        ];

        $boards = [
            1 => new Board(1),
            2 => new Board(2),
        ];

        return new self($boards, $players);
    }

    public function placeShip(int $boardId, Ship $ship, array $coordinates): void
    {
        /** @var Board $board */
        $board = $this->boards[$boardId];

        $board->placeShip($ship, $coordinates);
    }

    public function guess(int $boardId, Coordinate $coordinate): void
    {
        /** @var Board $board */
        $board = $this->boards[$boardId];

        $board->guess($coordinate);
    }
}