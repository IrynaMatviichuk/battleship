<?php

namespace Tests\Battleship\Domain;

use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Game;
use Battleship\Domain\GuessWasMade;
use Battleship\Domain\Ship;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function test_player_can_hit_ship(): void
    {
        $boards = [
            1 => new Board(1),
            2 => new Board(2),
        ];

        $ship = new Ship(1, 1, 3);

        $game = new Game($boards);

        $game->placeShip(
            1,
            $ship,
            [
                new Coordinate(0, 1),
                new Coordinate(0, 2),
                new Coordinate(0, 3),
            ],
        );

        $events = $boards[1]->recordedMessages();

        $this->assertCount(0, $events);

        $game->guess(1, new Coordinate(0, 2));

        $events = $boards[1]->recordedMessages();

        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);

        $this->assertTrue($event->isAHit());
    }
}