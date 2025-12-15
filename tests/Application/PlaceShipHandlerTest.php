<?php

namespace Tests\Battleship\Application;

use Battleship\Application\PlaceShip;
use Battleship\Application\PlaceShipHandler;
use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Ship;
use Battleship\Domain\Game;
use Battleship\Infrastructure\InMemoryBoardRepository;
use Battleship\Infrastructure\InMemoryShipRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PlaceShipHandlerTest extends TestCase
{
    public function test_it_places_ship(): void
    {
        $game = Game::startGame('game_id', 'board_id', 'board_2');
        $board = new Board('board_id', $game);
        $boards = new InMemoryBoardRepository([$board]);

        $ship = new Ship('ship_id', $board, 2);
        $ships = new InMemoryShipRepository([$ship]);

        $command = new PlaceShip($board->id, $ship->id, 2, 3, 'east');

        $placeShipHandler = new PlaceShipHandler($boards, $ships);

        $placeShipHandler->handle($command);

        $cell1 = $board->getCell(new Coordinate(2, 3));
        $cell2 = $board->getCell(new Coordinate(2, 4));

        $this->assertEquals($ship->id, $cell1->getShip());
        $this->assertEquals($ship->id, $cell2->getShip());
    }

    public function test_it_places_ship_from_other_board(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $game = new Game('game_id');
        $board = new Board('board_id', $game);
        $otherBoard = new Board('other_board_id', $game);
        $boards = new InMemoryBoardRepository([$board, $otherBoard]);

        $ship = new Ship('ship_id', $board, 2);
        $ships = new InMemoryShipRepository([$ship]);

        $command = new PlaceShip($otherBoard->id, $ship->id, 2, 3, 'east');

        $placeShipHandler = new PlaceShipHandler($boards, $ships);

        $placeShipHandler->handle($command);
    }

    public function test_it_places_ship_second_time(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $game = new Game('game_id');
        $board = new Board('board_id', $game);
        $otherBoard = new Board('other_board_id', $game);
        $boards = new InMemoryBoardRepository([$board, $otherBoard]);

        $ship = new Ship('ship_id', $board, 2);
        $ships = new InMemoryShipRepository([$ship]);

        $command = new PlaceShip($board->id, $ship->id, 2, 3, 'east');

        $placeShipHandler = new PlaceShipHandler($boards, $ships);

        $placeShipHandler->handle($command);

        $placeShipHandler->handle($command);
    }

    public function test_it_places_ship_out_of_board(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $game = new Game('game_id');
        $board = new Board('board_id', $game);
        $boards = new InMemoryBoardRepository([$board]);

        $ship = new Ship('ship_id', $board, 2);
        $ships = new InMemoryShipRepository([$ship]);

        $command = new PlaceShip($board->id, $ship->id, 9, 9, 'east');

        $placeShipHandler = new PlaceShipHandler($boards, $ships);

        $placeShipHandler->handle($command);
    }

    public function test_it_places_ship_over_ship(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $game = new Game('game_id');
        $board = new Board('board_id', $game);
        $boards = new InMemoryBoardRepository([$board]);

        $ship1 = new Ship('ship_id', $board, 2);
        $ship2 = new Ship('ship_id', $board, 3);
        $ships = new InMemoryShipRepository([$ship1, $ship2]);

        $command1 = new PlaceShip($board->id, $ship1->id, 0, 0, 'east');
        $command2 = new PlaceShip($board->id, $ship2->id, 0, 1, 'east');

        $placeShipHandler = new PlaceShipHandler($boards, $ships);

        $placeShipHandler->handle($command1);
        $placeShipHandler->handle($command2);
    }
}
