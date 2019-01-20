<?php

namespace Model;

use Phalcon\Mvc\Collection;

/**
 * use chip
 * db.createCollection("users");
 * db.members.createIndex({username: 1}, {unique: true});
 *
 */

class Users extends Collection
{
    private $id;
    private $username;
    private $password;
    private $messages;
}
