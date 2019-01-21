<?php

// Note: Many of these could come from env.
return [
    'url' => ['baseUri' => '/'],
    'beanstalk' => [
        // Note: TCP
        'host' => '127.0.0.1',
        'port' => '11300'
    ],
    'mongo' => ['host' => 'mongo', 'database' => 'chip'],
    'jwtAuth' => ['secretKey' => 'should not be static']
];
