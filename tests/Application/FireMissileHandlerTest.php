<?php

namespace Tests\Battleship\Application;

use Battleship\Application\FireMissile;
use Battleship\Application\FireMissileHandler;
use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\GuessWasMade;
use Battleship\Domain\Ship;
use Battleship\Infrastructure\InMemoryBoardRepository;
use PHPUnit\Framework\TestCase;

class FireMissileHandlerTest extends TestCase
{
    public function test_it_records_guess_was_made(): void
    {
        $board = new Board(1);
        $boards = new InMemoryBoardRepository([$board]);

        $command = new FireMissile(new Coordinate(0, 0), 1);

        $fireMissileHandler = new FireMissileHandler($boards);

        $this->assertEmpty($board->recordedMessages());

        $fireMissileHandler->handle($command);

        $this->assertCount(1, $board->recordedMessages());

        $events = $board->recordedMessages();
        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);
    }

    public function test_it_records_successful_guess(): void
    {
        $board = new Board('1');
        $ship = new Ship('1', $board, 2);

        $board->placeShip($ship, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);

        $boards = new InMemoryBoardRepository([$board]);

        $command = new FireMissile(new Coordinate(0, 0), 1);

        $fireMissileHandler = new FireMissileHandler($boards);

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
        $board = new Board('1');
        $ship = new Ship(1, $board, 2);

        $board->placeShip($ship, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);

        $boards = new InMemoryBoardRepository([$board]);

        $command = new FireMissile(new Coordinate(1, 1), 1);

        $fireMissileHandler = new FireMissileHandler($boards);

        $this->assertEmpty($board->recordedMessages());

        $fireMissileHandler->handle($command);

        $this->assertCount(1, $board->recordedMessages());

        $events = $board->recordedMessages();
        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);
        $this->assertFalse($event->isAHit());
    }
}
