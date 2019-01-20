<?php

namespace Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Filter;
use Library\Php;

final class MessageController extends Controller
{
    public function sendAction(): void
    {
        Php::assert($this->request->isPost());
        // Note: Filter string might convert it to string but we just want exists and is string check.
        // Note: This uses the HTTP query format (standard post) but JSON in and out would be better.
        $message = $this->request->getPost('message', [Filter::FILTER_STRING]);
        // Note: This depends on PHP's characterset to be configured properly.
        Php::assert(mb_check_encoding($message));
        return json_encode($this->queue->put(['user' => ?, 'message' => $message]));
    }

    public function listSentAction(): void
    {
        Php::assert($this->request->isGet());
        return json_encode(Users::findById(?)->messages);
    }
}
