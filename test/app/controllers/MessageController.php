<?php

namespace Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Filter;
use Library\Php;

final class MessageController extends Controller
{
    public function sendAction(): ?string
    {
        // Note: This should be abstracted away and DRY.
        if (!$this->jwt->check($this->request, $this->response)) {
            return null;
        }

        Php::assert($this->request->isPost());
        // Note: Filter string might convert it to string but we just want exists and is string check.
        // Note: This uses the HTTP query format (standard post) but JSON in and out would be better.
        $message = $this->request->getPost('message', [Filter::FILTER_STRING]);
        // Note: This depends on PHP's characterset to be configured properly.
        Php::assert(mb_check_encoding($message));

        // Note: This should be abstracted away and DRY.
        return json_encode($this->beanstalk->put(['user' => $this->jwt->getUserId(), 'message' => $message]));
    }

    public function sentAction(): ?string
    {
        if (!$this->jwt->check($this->request, $this->response)) {
            return null;
        }

        Php::assert($this->request->isGet());
        $user = $this->users->findById($this->jwt->getUserId());
        $reads = $user->reads++;
        Php::assert($user->save() !== false);

        return json_encode(compact('reads') + ['messages' => $user->messages]);
    }
}
