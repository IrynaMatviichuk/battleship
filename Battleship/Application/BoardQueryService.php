<?php

namespace Battleship\Application;

use Battleship\QueryModel\BoardDto;
use Illuminate\Support\Facades\DB;

class BoardQueryService
{
    public function getBoard(string $id): BoardDto
    {
        $board = DB::table('boards')->where('id', $id)->first();

        return new BoardDto($board->id, $board->size);
    }
}
