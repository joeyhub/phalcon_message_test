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
    public $id;
    /** @var string */
    public $username;
    /** @var string */
    public $password;
    /** @var int */
    public $reads;
    // Note: Could use a messages type class.
    /** @var array */
    public $messages;
}
