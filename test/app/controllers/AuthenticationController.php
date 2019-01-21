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

        return json_encode($this->jwt->make($this->users->login($username, $password)));
    }
}
