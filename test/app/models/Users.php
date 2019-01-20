<?php

namespace Model;

use Phalcon\Mvc\Collection;

/**
 * use chip
 * db.createCollection("users");
 * db.members.createIndex({username: 1}, {unique: true});
 */

class Users extends Collection
{
    /** @var string */
    private $id;
    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var int */
    private $reads;
    /** @var array Of what? */
    private $messages;
}
