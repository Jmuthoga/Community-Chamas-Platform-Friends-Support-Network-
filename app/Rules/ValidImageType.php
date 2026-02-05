<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class ValidImageType implements Rule
{
    public function passes($attribute, $value)
    {
        if (!$value->isValid()) return false; // Ensure file is uploaded properly

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];

        return in_array($value->getMimeType(), $allowedMimes);
    }

    public function message()
    {
        return 'The :attribute must be a valid image (JPEG, PNG, GIF, BMP, or WebP).';
    }
}
