<?php

namespace Controller;

use Phalcon\Mvc\Controller;
use Model\Users;
use Library\Php;

final class AuthenticationController extends Controller
{
    public functon getJwtTokenAction(): string
    {
        Php::assert($this->request->isPost());
        $username = $this->request->getPost('username', [Filter::FILTER_STRING]);
        $password = $this->request->getPost('password', [Filter::FILTER_STRING]);
        $password = $this->security->hash($password);
        $user = Users::findFirst([compact('username')]);
        Php::assert($user !== false);
        Php::assert($password === $user->password);

        return json_encode($this->auth->make($user->id));
    }
}
