<?php

namespace App\Http\Controllers;

use Battleship\Application\BoardQueryService;
use Illuminate\Http\JsonResponse;

class BoardController extends Controller
{
    public function __construct(private BoardQueryService $boardQueryService)
    {
    }

    public function show(string $id): JsonResponse
    {
        $board = $this->boardQueryService->getBoard($id);

        return new JsonResponse($board);
    }
}
