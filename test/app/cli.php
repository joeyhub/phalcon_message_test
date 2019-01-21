<?php

use Phalcon\Cli\Console;

call_user_func(function($argv): void {
    require_once(__DIR__ . '/../app/bootstrap.php');
    $bootstrap = Bootstrap::initialise(Bootstrap::CONTEXT_CLI);

    $arguments = [];

    foreach ($argv as $k => $arg) {
        if ($k === 1) {
            $arguments['task'] = $arg;
        } elseif ($k === 2) {
            $arguments['action'] = $arg;
        } elseif ($k >= 3) {
            if ($k === 3) {
                $arguments['params'] = [];
            }

            $arguments['params'][] = $arg;
        }
    }

    (new Console($bootstrap->getDi()))->handle($arguments);
}, $argv);
