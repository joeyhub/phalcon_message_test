<?php

namespace Library;

/**
 * This is a utility class providing static language functions for PHP.
 * These are functions that tend to be quite specific to PHP in general.
 */
/*final*/ abstract class Php
{
    /**
     * The intended use of this assert mechanism is for when people intend to put
     * in an assert for safety reasons rather than testing or debugging reasons
     * but due to time limitations want to keep is simple. Use of this function
     * should be seen as a "todo" or something that is likely to benefit from
     * for example a more relevant exception class. While this function
     * resembles the native PHP assert function it is not fully compatible
     * or identical.
     * @throws \Throwable Either $throwable or \AssertionError if $throwable not set.
     */
    public static function assert(bool $assertion, ?\Throwable $throwable = null): void
    {
        if ($assertion) {
            return;
        }

        throw $thorwable ?? new \AssertionError();
    }

    public static function assertIsInArray($value, array $array): void
    {
        self::assert(in_array($value, $array, true));
    }
}
