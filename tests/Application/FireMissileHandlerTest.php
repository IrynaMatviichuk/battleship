<?php

namespace Tests\Battleship\Application;

use Battleship\Application\FireMissile;
use Battleship\Application\FireMissileHandler;
use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Game;
use Battleship\Domain\GuessWasMade;
use Battleship\Domain\ShipHasSunk;
use Battleship\Domain\Ship;
use Battleship\Infrastructure\InMemoryBoardRepository;
use Battleship\Infrastructure\InMemoryGameRepository;
use PHPUnit\Framework\TestCase;

class FireMissileHandlerTest extends TestCase
{
    public function test_it_records_guess_was_made(): void
    {
        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $games = new InMemoryGameRepository([$game]);

        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

        $board = $game->getBoards()['board_1'];

        $command = new FireMissile(new Coordinate(0, 0), 'game_id', 'board_1');

        $fireMissileHandler = new FireMissileHandler($games);

        $this->assertEmpty($board->recordedMessages());

        $fireMissileHandler->handle($command);

        $this->assertCount(1, $board->recordedMessages());

        $events = $board->recordedMessages();
        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);
    }

    public function test_it_records_successful_guess(): void
    {
        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $games = new InMemoryGameRepository([$game]);

        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

        $board = $game->getBoards()['board_1'];

        $ship = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });

        $board->placeShip($ship->id, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);

        $command = new FireMissile(new Coordinate(0, 0), 'game_id', 'board_1');

        $fireMissileHandler = new FireMissileHandler($games);

        $this->assertEmpty($board->recordedMessages());

        $fireMissileHandler->handle($command);

        $this->assertCount(1, $board->recordedMessages());

        $events = $board->recordedMessages();
        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);
        $this->assertTrue($event->isAHit());
    }

    public function test_it_records_unsuccessful_guess(): void
    {
        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $games = new InMemoryGameRepository([$game]);

        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

        $board = $game->getBoards()['board_1'];

        $ship = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });

        $board->placeShip($ship->id, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);

        $command = new FireMissile(new Coordinate(1, 1), 'game_id', 'board_1');

        $fireMissileHandler = new FireMissileHandler($games);

        $this->assertEmpty($board->recordedMessages());

        $fireMissileHandler->handle($command);

        $this->assertCount(1, $board->recordedMessages());

        $events = $board->recordedMessages();
        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);
        $this->assertFalse($event->isAHit());
    }

    public function test_it_records_ship_has_sunk(): void
    {
        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $games = new InMemoryGameRepository([$game]);

        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

        $board = $game->getBoards()['board_1'];

        $ship = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });

        $board->placeShip($ship->id, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);

        $boards = new InMemoryBoardRepository([$board]);

        $command1 = new FireMissile(new Coordinate(0, 0), 'game_id', 'board_1');
        $command2 = new FireMissile(new Coordinate(0, 1), 'game_id', 'board_1');

        $fireMissileHandler = new FireMissileHandler($games);

        $this->assertEmpty($board->recordedMessages());

        $fireMissileHandler->handle($command1);
        $fireMissileHandler->handle($command2);

        $events = $board->recordedMessages();

        $this->assertCount(3, $events);

        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);

        $event = $events[1];
        $this->assertInstanceOf(GuessWasMade::class, $event);

        $event = $events[2];
        $this->assertInstanceOf(ShipHasSunk::class, $event);
    }
}
