<?php


namespace Sunfire\Form\Services;


use Illuminate\Support\Arr;

class Options
{
    public static function getOption(string $key, $options)
    {
        $result = Arr::get($options, $key);

        if (is_array($result)) {
            return join(' ', Arr::flatten(
                $result
            ));
        }

        return $result;

    }
}