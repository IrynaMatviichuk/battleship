<?php

namespace Tests\Battleship\Functional;

use Battleship\Application\FireMissile;
use Battleship\Application\FireMissileHandler;
use Battleship\Application\PlaceShip;
use Battleship\Application\StartGame;
use Battleship\Application\StartGameHandler;
use Battleship\Application\PlaceShipHandler;
use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Game;
use Battleship\Domain\GameOver;
use Battleship\Domain\Ship;
use Battleship\Infrastructure\InMemoryBoardRepository;
use Battleship\Infrastructure\InMemoryGameRepository;
use Battleship\Infrastructure\InMemoryShipRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function test_game_is_over(): void
    {
        $games = new InMemoryGameRepository([]);
        $boards = new InMemoryBoardRepository([]);

        $gameId = Str::uuid();
        $boardId1 = Str::uuid();
        $boardId2 = Str::uuid();

        $startGameHandler = new StartGameHandler($games);
        $startGameHandler->handle(new StartGame($gameId, [$boardId1, $boardId2]));

        $game = $games->findById($gameId);
        $board1 = $game->getBoards()[$boardId1->toString()];
        $board2 = $game->getBoards()[$boardId2->toString()];

        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $ships = new InMemoryShipRepository([...$board1->getShips(), ...$board2->getShips()]);

        $events1 = $board1->recordedMessages();
        $events2 = $board2->recordedMessages();

        $this->assertCount(0, $events1);
        $this->assertCount(0, $events2);

        $placeShipHandler = new PlaceShipHandler($games, $boards, $ships);
        $fireMissileHandler = new FireMissileHandler($games);

        $this->placeShips($game, $board1, $placeShipHandler);
        $this->placeShips($game, $board2, $placeShipHandler);

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

        $this->fireMissiles($game, $board1, $fireMissileHandler);
        $this->fireMissiles($game, $board2, $fireMissileHandler);

        foreach ($board1->getShips() as $ship) {
            $this->assertTrue($ship->sunk());
        }

        foreach ($board2->getShips() as $ship) {
            $this->assertTrue($ship->sunk());
        }

        $board1Events = $board1->recordedMessages();
        $this->assertCount(23, $board1Events);

        $board1GameOverEvent = $board1Events[22];
        $this->assertInstanceOf(GameOver::class, $board1GameOverEvent);

        $board2Events = $board2->recordedMessages();
        $this->assertCount(23, $board2Events);

        $board2GameOverEvent = $board2Events[22];
        $this->assertInstanceOf(GameOver::class, $board2GameOverEvent);
    }

    private function placeShips(Game $game, Board $board, PlaceShipHandler $handler): void
    {
        foreach ($board->getShips()->getValues() as $key => $ship) {
            $command = new PlaceShip($game->id, $board->id, $ship->id, $key * 2, $key + 1, 'east');
            $handler->handle($command);
        }
    }

    private function fireMissiles(Game $game, Board $board, FireMissileHandler $handler): void
    {
        foreach ($board->getShips()->getValues() as $key => $ship) {
            $shipCoordinates = $this->getCoordinates($ship->size, $key);
            foreach ($shipCoordinates as $coordinate) {
                $command = new FireMissile($coordinate, $game->id, $board->id);

                $handler->handle($command);
            }
        }
    }

    private function getCoordinates(int $size, int $index): array
    {
        $coordinates = [];

        for ($shift = 1; $shift <= $size; $shift++) {
            $coordinates[] = new Coordinate($index * 2, $shift + $index);
        }

        return $coordinates;
    }
}

