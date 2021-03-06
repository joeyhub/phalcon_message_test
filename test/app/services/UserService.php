<?php

namespace Service;

use Phalcon\Di\Injectable;
use Library\Php;

class UserService extends Injectable
{
    /** @var string */
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function createCollection(): void
    {
        $this->mongo->createCollection('users');
        $this->mongo->selectCollection('users')->createIndex(['username' => 1], ['unique' => true]);
    }

    public function create(): void
    {
        $class = $this->class;
        $user = new $class();
        $user->username = 'test';
        $user->password = $this->security->hash('password');

        if ($user->save() === false) {
            Php::assert(false, implode("\n", $user->getMessages()));
        }
    }

    public function getById(string $id)
    {
        $class = $this->class;
        $user = $class::findById($id);
        $this->assertIsUser($user);

        return $user;
    }

    public function login(string $username, string $password): string
    {
        $class = $this->class;
        $user = $class::findFirst([compact('username')]);
        $this->assertIsUser($user);
        Php::assert($this->security->checkHash($password, $user->password));

        return $user->getId();
    }

    public function assertIsUser($user): void
    {
        Php::assert(is_object($user) && is_a($user, $this->class));
    }
}
