<?php

namespace App\Console\Commands;

use Battleship\Application\MakeGuess as MakeGuessCommand;
use Battleship\Shared\CommandBus;
use Illuminate\Console\Command;

class MakeGuess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:make-guess {boardId} {row} {column}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(CommandBus $commandBus): void
    {
        $boardId = $this->argument('boardId');
        $row = (int)$this->argument('row');
        $column = (int)$this->argument('column');

        $command = new MakeGuessCommand($boardId, $row, $column);

        $commandBus->handle($command);
    }
}
