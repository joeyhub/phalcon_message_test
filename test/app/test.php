<?php

use Phalcon\Cli\Console;
use PHPUnit\TextUI\Command;

require_once __DIR__ . '/../app/bootstrap.php';
Bootstrap::initialise(Bootstrap::CONTEXT_CLI, Bootstrap::ENVIRONMENT_TEST);
Command::main();
