<?php

namespace Task;

use Phalcon\Cli\Task;
use Model\Users;

class SetupTask extends Task
{
    public function createUserCollectionAction(): void
    {
        $this->users->createCollection();
    }

    public function createTestUserAction(): void
    {
        $this->users->create('test', 'password');
    }
}
