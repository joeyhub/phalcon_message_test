<?php

use Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    public function setUp()
    {
        parent::setUp();

        $di = Di::getDefault();
        $this->setDi(Bootstrap::getInstance()->getDi());
    }
}
