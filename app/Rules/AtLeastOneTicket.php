<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneTicket implements Rule
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function passes($attribute, $value)
    {
        foreach ($this->categories as $category) {
            $categoryId = is_object($category) ? $category->id : $category;

            if (array_key_exists($categoryId, $value) && !empty($value[$categoryId])) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'At least one ticket must be selected from all categories.';
    }
}
