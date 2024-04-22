<?php

namespace Tests\Battleship\Domain;

use Battleship\Domain\Board;
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

        $board->guess( 3, 4);

        $cell = $board->getCell(3, 4);

        $this->assertFalse($cell->isGuessed());
    }

    public function test_guess_is_hit(): void
    {
        $board = new Board();

        $ship = new Ship(2);
        $ship->place([[0, 0], [0, 1]]);

        $board->placeShip($ship);

        $board->guess(0, 0);

        $cell = $board->getCell(0, 0);

        $this->assertTrue($cell->isGuessed());
    }

    public function test_it_throws_on_collision(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $board = new Board();

        $ship = new Ship(2);
        $ship->place([[0, 0], [0, 1]]);

        $board->placeShip($ship);

        $ship2 = new Ship(2);
        $ship2->place([[0, 0], [0, 1]]);

        $board->placeShip($ship2);
    }
}
