<?php

namespace ahvla\laravel\helper;

class UtilityHelper {

    /**
     * Optionally prepend a value to the beginning of the array (eg 'choose'/'all' etc)
     * Ensure keys have no spaces by converting them to underscores
     * @param $data
     * @param $prependArr
     * @return array
     */
    public static function formatDropdownData($data, $prependArr=[])
    {
        if (!empty($prependArr)) {
            $data = array_merge($prependArr, $data);
        }
        return $data;
        //return self::array_map_keys(function($str){return str_replace(' ', '_', $str);}, $data);
    }

    public static function unformatDropdownSelection($str)
    {
        return str_replace('_', ' ', $str);
    }

    /**
     * Apply array_map to the keys of an array.
     * Extra array arguments will be used for the callback function's parameters just like with array_map,
     * with the difference that a string is also allowed: it will just be used to create an array of appropriate length
     * with each value as that string.
     * Arrays are left alone (and will be padded with nulls by array_map as needed)
     * @param $callback
     * @param $array
     * @return array
     */
    public static function array_map_keys($callback, $array /* [, $args ..] */) {
        $args = func_get_args();
        $args[1] = array_keys($array);
        // If any additional arguments are not arrays, assume that value is wanted for every $array item.
        // array_map() will pad shorter arrays with null values
        for ($i=2; $i < count($args); $i++) {
            if (! is_array($args[$i])) {
                $args[$i] = array_fill(0, count($array), $args[$i]);
            }
        }
        return array_combine(call_user_func_array('array_map', $args), $array);
    }
}