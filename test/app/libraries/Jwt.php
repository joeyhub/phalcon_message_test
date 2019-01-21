<?php

namespace Library;

use Phalcon\Http\RequestInterface;
use Phalcon\Http\ResponseInterface;
// Note: Dmkit has quality issues, it should be kept behind this wrapper.
use Dmkit\Phalcon\Auth\Auth;
use Dmkit\Phalcon\Auth\TokenGetter\TokenGetter;
use Dmkit\Phalcon\Auth\TokenGetter\Handler\Header;
use Dmkit\Phalcon\Auth\TokenGetter\Handler\QueryStr;

class Jwt
{
    /** @var string */
    private $secretKey;
    /** @var Auth */
    private $auth;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
        $this->auth = new Auth();
    }

    public function make(string $userId): string
    {
        return $this->auth->make(compact('userId'), $this->secretKey);
    }

    public function check(RequestInterface $request, ResponseInterface $response): bool
    {
        $getter = new TokenGetter(new Header($request));

        if ($this->auth->check($getter, $this->secretKey)) {
            return true;
        }

        // Note: This could include authorise location.
        // Note: Authentication shouldn't really be coupled with IO concerns.
        $response->setStatusCode(401, 'Unauthorized');

        return false;
    }

    public function getUserId(): string
    {
        return $this->auth->data('userId');
    }
}
