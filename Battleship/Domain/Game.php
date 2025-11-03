<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;

class Game
{
    use EventRecorder;

    private string $id;
    private array $boards;

    private array $players;

    private Phase $phase = Phase::PLACE_SHIPS;

    private function __construct(string $id, string $boards, array $players)
    {
        $this->id = $id;
        $this->boards = $boards;
        $this->players = $players;
    }

    public static function startGame(string $id, string $playerId): self
    {
        $players = [
            1 => new Player($playerId),
        ];

        $boards = [
            1 => new Board(1, $id),
            2 => new Board(2, $id),
        ];

        return new self($id, $boards, $players);
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
