<?php

function isAssoc(array $arr)
{
    if (!is_array($arr) || count($arr) < 1) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}

/**
 * Version of array_merge_recursive without overwriting numeric keys
 *
 * @param  array $array1 Initial array to merge.
 * @param  array ...     Variable list of arrays to recursively merge.
 *
 */
function deepMerge()
{
    $arrays = func_get_args();
    $base = [];
    foreach($arrays as $array) {
        while(list($key, $value) = @each($array)) {
            if(is_array($value) && isAssoc($value)) {
                $base[$key] = deepMerge($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }
    }

    return $base;
}

/**
 * Set an array item to a given value using "dot" notation.
 * If no key is given to the method, the entire array will be replaced.
 *
 * @param  array  $array
 * @param  string|null  $key
 * @param  mixed  $value
 * 
 * @return array
 */
function arraySet(&$array, $key, $value)
{
    if (is_null($key)) {
        return $array = $value;
    }

    $keys = explode('.', $key);

    foreach ($keys as $i => $key) {
        if (count($keys) === 1) {
            break;
        }

        unset($keys[$i]);

        // If the key doesn't exist at this depth, we will just create an empty array
        // to hold the next value, allowing us to create the arrays to hold final
        // values at the correct depth. Then we'll keep digging into the array.
        if (! isset($array[$key]) || ! is_array($array[$key])) {
            $array[$key] = [];
        }

        $array = &$array[$key];
    }

    $array[array_shift($keys)] = $value;

    return $array;
}
