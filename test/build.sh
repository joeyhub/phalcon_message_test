#!/bin/bash

composer install

php app/cli.php Setup createUserCollection
php app/cli.php Setup createTestUser
