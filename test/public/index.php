<?php

use Phalcon\Mvc\Application;

call_user_func(function(): void {
    require_once(__DIR__ . '/../app/bootstrap.php');
    $bootstrap = Bootstrap::initialise(Bootstrap::CONTEXT_WEB);

    try {
        (new Application($bootstrap->getDi()))->handle()->send();
    } catch (Exception $e) {
        echo 'Exception: ', $e->getMessage();
    }
});
