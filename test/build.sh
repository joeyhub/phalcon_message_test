#!/bin/bash

composer install

php app/cli.php Setup createTestUser
