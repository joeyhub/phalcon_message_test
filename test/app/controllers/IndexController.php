<?php

namespace Controller;

use Phalcon\Mvc\Controller;

final class IndexController extends Controller
{
    public function indexAction(): void
    {
        $this->response->setStatusCode(404, 'Not found.');
    }
}
