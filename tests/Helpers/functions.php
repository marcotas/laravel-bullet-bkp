<?php

/**
 * Create a model of the given class.
 *
 * @param \App\Models\Model $class
 * @param array $attributes
 * @param int $quantity
 * @return mixed
 */
function create($class, $attributes = [], $quantity = null)
{
    return factory($class, $quantity)->create($attributes);
}

/**
 * Make a model of the given class.
 *
 * @param \App\Models\Model $class
 * @param array $attributes
 * @param int $quantity
 * @return mixed
 */
function make($class, $attributes = [], $quantity = null)
{
    return factory($class, $quantity)->make($attributes);
}
