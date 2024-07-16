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
        foreach ($this->categories as $categoryId) {
            if (!array_key_exists($categoryId, $value)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'You must select at least one ticket from each category.';
    }
}
