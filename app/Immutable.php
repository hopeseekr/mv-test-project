<?php

namespace App;

/**
 * For models which should never be modified.
 * Perfect for SQL views.
 */
trait Immutable
{
    public static function boot()
    {
        static::saving(function ($model) {
            throw new \LogicException('This Entity is immutable and cannot be modified');
        });
    }
}
