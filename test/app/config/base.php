<?php

// Note: Many of these could come from env.
return [
    'url' => ['baseUri' => '/'],
    'beanstalk' => [
        // Note: TCP
        'host' => 'beanstalk',
        'port' => '11300'
    ],
    'mongo' => ['host' => 'mongo', 'database' => 'chip'],
    'jwt' => ['secretKey' => 'should not be static']
];
