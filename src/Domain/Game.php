<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;

class Game
{
    use EventRecorder;

    private array $boards;

    private array $players;

    private Phase $phase = Phase::PLACE_SHIPS;

    public function __construct(array $boards, array $players)
    {
        $this->boards = $boards;
        $this->players = $players;
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
        if ($this->phase !== Phase::PLACE_SHIPS) {
            throw new \InvalidArgumentException();
        }

        /** @var Board $board */
        $board = $this->boards[$boardId];

        $board->placeShip($ship, $coordinates);
    }

    public function guess(int $boardId, Coordinate $coordinate): void
    {
        if ($this->phase !== Phase::BATTLE) {
            throw new \InvalidArgumentException();
        }

        /** @var Board $board */
        $board = $this->boards[$boardId];

        $board->guess($coordinate);
    }

    public function markPlayerReady(int $playerId): void
    {
        /** @var Player $player */
        $player = $this->players[$playerId];

        $player->markReady();

        $this->checkAllPlayersReady();
    }

    private function checkAllPlayersReady(): void
    {
        $allPlayersReady = true;

        /** @var Player $player */
        foreach ($this->players as $player) {
            if (!$player->isReady()) {
                $allPlayersReady = false;
            }
        }

        if ($allPlayersReady) {
            $this->phase = Phase::BATTLE;

            $this->record(new BattleHasBegun());
        }
    }
}
