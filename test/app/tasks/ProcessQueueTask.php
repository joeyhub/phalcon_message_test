<?php

namespace Task;

use Phalcon\Cli\Task;
use Model\Users;
use Library\Php;

class ProcessQueueTask extends Task
{
    public function mainAction()
    {
        while (($job = $this->beanstalk->reserve()) !== false) {
            $message = $job->getBody();
            $user = Users::findById($message['user']);
            $user->messages[] = $message['message'];
            Php::assert($user->save() !== false);

            $job->delete();
        }
    }
}
