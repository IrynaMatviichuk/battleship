<?php

namespace Tests\Battleship\Domain;

use Battleship\Domain\Board;
use Battleship\Domain\Cell;
use Battleship\Domain\Coordinate;
use Battleship\Domain\GuessWasMade;
use Battleship\Domain\Ship;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    public function test_it_creates_board(): void
    {
        $this->expectNotToPerformAssertions();

        new Board();
    }

    public function test_guess_is_miss(): void
    {
        $board = new Board();

        $board->guess(new Coordinate(3, 4));

        $cell = $board->getCell(new Coordinate(3, 4));

        $this->assertFalse($cell->isGuessed());
    }

    public function test_guess_is_hit(): void
    {
        $board = new Board();

        $ship = new Ship(2);

        $board->placeShip($ship, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);

        $board->guess(new Coordinate(0, 0));

        $cell = $board->getCell(new Coordinate(0, 0));

        $this->assertTrue($cell->isGuessed());
    }

    public function test_it_throws_on_collision(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $board = new Board();

        $ship = new Ship(2);

        $board->placeShip($ship, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);

        $ship2 = new Ship(2);

        $board->placeShip($ship2, [
            new Coordinate(0, 0),
            new Coordinate(0, 1),
        ]);
    }

    public function test_guess_was_made_recorded(): void
    {
        $board = new Board();

        $events = $board->recordedMessages();

        $this->assertCount(0, $events);

        $board->guess(new Coordinate(0, 0));

        $events = $board->recordedMessages();

        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);

        $this->assertFalse($event->isAHit());

        $this->assertTrue($event->getCoordinate()->matches(new Coordinate(0, 0)));
    }
}
