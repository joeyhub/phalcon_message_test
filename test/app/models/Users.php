<?php

namespace Model;

use Phalcon\Mvc\MongoCollection;

class Users extends MongoCollection
{
    /** @var string */
    public $username;
    /** @var string */
    public $password;
    /** @var int */
    public $reads = 0;
    // Note: Could use a messages type class.
    /** @var array */
    public $messages = [];
}
