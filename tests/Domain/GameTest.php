<?php

namespace Tests\Battleship\Domain;

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
        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $game->placeShip(
            'ship_id',
            'board_1',
            3,
            [
                new Coordinate(0, 1),
                new Coordinate(0, 2),
                new Coordinate(0, 3),
            ],
        );

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

//        $events = $boards[1]->recordedMessages();
//
//        $this->assertCount(0, $events);
//
        $game->guess('board_1', new Coordinate(0, 2));

//        $events = $boards[1]->recordedMessages();
//
//        $this->assertCount(1, $events);
//
//        $event = $events[0];
//        $this->assertInstanceOf(GuessWasMade::class, $event);
//
//        $this->assertTrue($event->isAHit());
    }

    public function test_player_cant_guess_during_place_ships_phase(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $game->guess('board_1', new Coordinate(0, 2));
    }

    public function test_player_cant_place_ship_during_battle_phase(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

        $game->placeShip(
            'ship_id',
            'board_id',
            3,
            [
                new Coordinate(0, 1),
                new Coordinate(0, 2),
                new Coordinate(0, 3),
            ],
        );
    }

    public function test_battle_has_begun_recorded(): void
    {
        $game = Game::startGame('game_id', 'board_1', 'board_2');
        $game->addPlayer('player_1');
        $game->addPlayer('player_2');

        $events = $game->recordedMessages();

        $this->assertCount(0, $events);

        $game->markPlayerReady('player_1');
        $game->markPlayerReady('player_2');

        $events = $game->recordedMessages();

        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertInstanceOf(BattleHasBegun::class, $event);
    }
}
