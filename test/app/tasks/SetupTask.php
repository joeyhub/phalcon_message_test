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
        $user->password = $this->security->hash('password');
        // Note: Might be more or less efficient to keep it empty when empty depending on scenario.
        $user->messages = [];

        if ($user->save() === false) {
            Php::assert(false, implode("\n", $user->getMessages()));
        }
    }
}
