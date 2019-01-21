<?php

namespace Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Filter;
use Model\Users;
use Library\Php;

final class AuthenticationController extends Controller
{
    public function getJwtAction(): string
    {
        Php::assert($this->request->isPost());
        $username = $this->request->getPost('username', [Filter::FILTER_STRING]);
        $password = $this->request->getPost('password', [Filter::FILTER_STRING]);
        $user = Users::findFirst([compact('username')]);
        Php::assert($user !== false);
        Php::assert($this->security->checkHash($password, $user->password));

        return json_encode($this->jwt->make($user->getId()));
    }
}
