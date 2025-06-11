<?php

namespace App\Console\Commands;

use Battleship\Domain\Coordinate;
use Battleship\Application\FireMissile as FireMissileCommand;
use Battleship\Shared\CommandBus;
use Illuminate\Console\Command;

class FireMissile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:fire-missile {boardId} {row} {column}';

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

        $command = new FireMissileCommand(new Coordinate($row, $column), $boardId);

        $commandBus->handle($command);
    }
}
