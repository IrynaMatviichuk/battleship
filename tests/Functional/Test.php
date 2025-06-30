<?php

namespace Tests\Battleship\Functional;

use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\GameOver;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function test_game_is_over(): void
    {
        $board = new Board('board_id');
        $ships = $board->getShips();

        $events = $board->recordedMessages();
        $this->assertCount(0, $events);

        foreach ($ships as $key => $ship) {
            $board->placeShip($ship, $this->getCoordinates($ship->size, $key));
        }

        foreach ($ships as $key => $ship) {
            $shipCoordinates = $this->getCoordinates($ship->size, $key);
            foreach ($shipCoordinates as $coordinate) {
                $board->guess($coordinate);
            }
        }

        foreach ($ships as $ship) {
            $this->assertTrue($ship->sunk());
        }

        $events = $board->recordedMessages();
        $this->assertCount(23, $events);

        $gameOverEvent = $events[22];
        $this->assertInstanceOf(GameOver::class, $gameOverEvent);
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

