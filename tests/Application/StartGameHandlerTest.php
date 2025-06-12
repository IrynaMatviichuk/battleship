<?php

namespace Tests\Battleship\Application;

use Battleship\Application\StartGame;
use Battleship\Application\StartGameHandler;
use Battleship\Infrastructure\InMemoryBoardRepository;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class StartGameHandlerTest extends TestCase
{
    public function test_it_creates_boards(): void
    {
        $boards = new InMemoryBoardRepository([]);

        $boardId1 = Str::uuid();
        $boardId2 = Str::uuid();

        $command = new StartGame([$boardId1, $boardId2]);

        $startGameHandler = new StartGameHandler($boards);

        $startGameHandler->handle($command);

        $board1 = $boards->findById($boardId1);
        $board2 = $boards->findById($boardId2);

        $this->assertEquals($boardId1, $board1->id);
        $this->assertEquals($boardId2, $board2->id);
    }
}
