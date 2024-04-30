<?php

use App\Enum\TestHistoryState;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
require dirname(__DIR__) . '/vendor/autoload.php';

$bootstrapFileExists = file_exists(dirname(__DIR__) . '/config/bootstrap.php');
if ($bootstrapFileExists) {
    require dirname(__DIR__) . '/config/bootstrap.php';
}

if (! $bootstrapFileExists && method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}


$processes = [
//    // Delete the "tests" database.
    new Process(['php', 'bin/console', 'doctrine:database:drop', '-n', '--force', '--if-exists']),
    // Create the "tests" database.
    new Process(['php', 'bin/console', 'doctrine:database:create', '-n', '--if-not-exists']),
    // Create tables.
    new Process(['php', 'bin/console', 'doctrine:schema:update', '--complete', '-f']),
    // Clear the cache.
    new Process(['php', 'bin/console', 'cache:clear', '--no-warmup']),

    new Process(['composer', 'load-fixtures']),
];

foreach ($processes as $key => $process) {
    $process->run();
    if (! $process->isSuccessful()) {
        throw new RuntimeException(
            $process->getCommandLine() .
            ': ' .
            $process->getExitCode() .
            ' ' .
            $process->getExitCodeText() .
            ' ' .
            $process->getErrorOutput(),
        );
    }
}

TestHistoryState::disable();
