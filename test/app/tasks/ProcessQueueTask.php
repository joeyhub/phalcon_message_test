<?php

namespace Task;

use Phalcon\Cli\Task;

class ProcessQueueTask extends Task
{
    public function mainAction()
    {
        while (($job = $queue->reserve()) !== false) {
            $message = $job->getBody();
            $user = Users::findById()
            $user->messages[] = $message['message'];
            $user->reads++;
            $user->save();

            $job->delete();
        }
    }
}
