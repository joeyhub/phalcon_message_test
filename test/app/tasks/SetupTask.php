<?php

namespace Task;

use Phalcon\Cli\Task;
use Model\Users;

class SetupTask extends Task
{
    public function createTestUserAction()
    {
        $user = new Users();
        $user->username = 'test';
        $user->password = 'password';
        // Note: Might be more or less efficient to keep it empty when empty depending on scenario.
        $user->messages = [];
var_dump("no");
        if ($robot->save() === false) {
            Php::assert(false, implode("\n", $user->getMessages()));
        }
    }
}
