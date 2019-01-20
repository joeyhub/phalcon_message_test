<?php

namespace Task;

use Phalcon\Cli\Task;

class ProcessQueueTask extends Task
{
    public function mainAction()
    {
        while (($job = $queue->reserve()) !== false) {
            $message = $job->getBody();
            

            $job->delete();
        }
    }
}
