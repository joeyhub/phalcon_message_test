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

        if ($user->save() === false) {
            Php::assert(false, implode("\n", $user->getMessages()));
        }
    }
}
