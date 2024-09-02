<?php

namespace Tests\Battleship\Application;

use Battleship\Application\FireMissile;
use Battleship\Application\FireMissileHandler;
use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
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
        $this->assertTrue($event->isHit());
    }
}
