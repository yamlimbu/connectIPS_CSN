<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    /**
     * Generate a unique random string.
     *
     * @param int $length
     * @param string $column
     * @param string $model
     * @return string
     */
    public static function generateUniqueRandomString($length = 16, $column, $model)
    {
        $randomString = Str::random($length);

        // Check if the string is unique
        while ($model::where($column, $randomString)->exists()) {
            $randomString = Str::random($length);
        }

        return $randomString;
    }
}
