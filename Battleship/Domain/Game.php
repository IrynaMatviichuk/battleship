<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity]
class Game
{
    use EventRecorder;

    #[Id, Column(type: "string", unique: true)]
    public readonly string $id;

    #[Column(type: 'phase')]
    private Phase $phase = Phase::PLACE_SHIPS;

    #[OneToMany(targetEntity: Board::class, mappedBy: 'game', cascade: ['persist'])]
    private Collection $boards;

    private array $players;

    private function __construct(string $id)
    {
        $this->id = $id;
        $this->boards = new ArrayCollection();
        $this->players = [];
    }

    public static function startGame(string $id, string $board1Id, string $board2Id): self
    {
        $game = new self($id);

        $game->boards[$board1Id] = new Board($board1Id, $game);
        $game->boards[$board2Id] = new Board($board2Id, $game);

        return $game;
    }

    public function getBoards(): Collection
    {
        return $this->boards;
    }

    public function addPlayer(string $playerId): void
    {
        $this->players[$playerId] = new Player($playerId, $this->id);
    }

    public function placeShip(string $shipId, string $boardId, array $coordinates): void
    {
        if ($this->phase !== Phase::PLACE_SHIPS) {
            throw new \InvalidArgumentException();
        }

        /** @var Board $board */
        $board = $this->boards[$boardId];

        $board->placeShip($shipId, $coordinates);
    }

    public function guess(string $boardId, Coordinate $coordinate): void
    {
        if ($this->phase !== Phase::BATTLE) {
            throw new \InvalidArgumentException();
        }

        /** @var Board $board */
        $board = $this->boards[$boardId];

        $board->guess($coordinate);
    }

    public function markPlayerReady(string $playerId): void
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
