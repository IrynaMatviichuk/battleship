<?php

namespace Tests\Battleship\Application;

use Battleship\Domain\BattleHasBegun;
use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Game;
use Battleship\Domain\GuessWasMade;
use Battleship\Domain\Player;
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

        $players = [
            1 => new Player(1),
            2 => new Player(2),
        ];

        $game = new Game($boards, $players);

        $ship = new Ship(1, 1, 3);

        $game->placeShip(
            1,
            $ship,
            [
                new Coordinate(0, 1),
                new Coordinate(0, 2),
                new Coordinate(0, 3),
            ],
        );

        $game->markPlayerReady(1);
        $game->markPlayerReady(2);

        $events = $boards[1]->recordedMessages();

        $this->assertCount(0, $events);

        $game->guess(1, new Coordinate(0, 2));

        $events = $boards[1]->recordedMessages();

        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertInstanceOf(GuessWasMade::class, $event);

        $this->assertTrue($event->isAHit());
    }

    public function test_player_cant_guess_during_place_ships_phase(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $boards = [
            1 => new Board(1),
            2 => new Board(2),
        ];

        $players = [
            1 => new Player(1),
            2 => new Player(2),
        ];

        $game = new Game($boards, $players);

        $game->guess(1, new Coordinate(0, 2));
    }

    public function test_player_cant_plan_ship_during_battle_phase(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $boards = [
            1 => new Board(1),
            2 => new Board(2),
        ];

        $players = [
            1 => new Player(1),
            2 => new Player(2),
        ];

        $game = new Game($boards, $players);

        $game->markPlayerReady(1);
        $game->markPlayerReady(2);

        $ship = new Ship(1, 1, 3);

        $game->placeShip(
            1,
            $ship,
            [
                new Coordinate(0, 1),
                new Coordinate(0, 2),
                new Coordinate(0, 3),
            ],
        );
    }

    public function test_battle_has_begun_recorded(): void
    {
        $boards = [
            1 => new Board(1),
            2 => new Board(2),
        ];

        $players = [
            1 => new Player(1),
            2 => new Player(2),
        ];

        $game = new Game($boards, $players);

        $events = $game->recordedMessages();

        $this->assertCount(0, $events);

        $game->markPlayerReady(1);
        $game->markPlayerReady(2);

        $events = $game->recordedMessages();

        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertInstanceOf(BattleHasBegun::class, $event);
    }
}
