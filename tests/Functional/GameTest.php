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
use Battleship\Domain\GameOver;
use Battleship\Domain\Ship;
use Battleship\Infrastructure\InMemoryBoardRepository;
use Battleship\Infrastructure\InMemoryShipRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function test_game_is_over(): void
    {
        $boards = new InMemoryBoardRepository([]);

        $boardId1 = Str::uuid();
        $boardId2 = Str::uuid();

        $startGameHandler = new StartGameHandler($boards);
        $startGameHandler->handle(new StartGame([$boardId1, $boardId2]));

        $board1 = $boards->findById($boardId1);
        $board2 = $boards->findById($boardId2);

        $ships = new InMemoryShipRepository([...$board1->getShips(), ...$board2->getShips()]);

        $events1 = $board1->recordedMessages();
        $events2 = $board2->recordedMessages();

        $this->assertCount(0, $events1);
        $this->assertCount(0, $events2);

        $placeShipHandler = new PlaceShipHandler($boards, $ships);
        $fireMissileHandler = new FireMissileHandler($boards);

        $this->placeShips($board1, $placeShipHandler);
        $this->placeShips($board2, $placeShipHandler);

        $this->fireMissiles($board1, $fireMissileHandler);
        $this->fireMissiles($board2, $fireMissileHandler);

        foreach ($board2->getShips() as $ship) {
            $this->assertTrue($ship->sunk());
        }

        foreach ($board2->getShips() as $ship) {
            $this->assertTrue($ship->sunk());
        }

        $board1Events = $board1->recordedMessages();
        $this->assertCount(23, $board1Events);

        $board1GameOverEvent = $board1Events[22];
        $this->assertInstanceOf(GameOver::class, $board1GameOverEvent);

        $board2Events = $board1->recordedMessages();
        $this->assertCount(23, $board1Events);

        $board2GameOverEvent = $board2Events[22];
        $this->assertInstanceOf(GameOver::class, $board2GameOverEvent);
    }

    private function placeShips(Board $board, PlaceShipHandler $handler): void
    {
        foreach ($board->getShips() as $key => $ship) {
            $command = new PlaceShip($board->id, $ship->id, $key * 2, $key + 1, 'east');
            $handler->handle($command);
        }
    }

    private function fireMissiles(Board $board, FireMissileHandler $handler): void
    {
        foreach ($board->getShips() as $key => $ship) {
            $shipCoordinates = $this->getCoordinates($ship->size, $key);
            foreach ($shipCoordinates as $coordinate) {
                $command = new FireMissile($coordinate, $board->id);

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

