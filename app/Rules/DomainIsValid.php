<?php

namespace App\Rules;

use App\Models\Domain;
use App\Models\ReservedDomain;
use Illuminate\Contracts\Validation\Rule;

class DomainIsValid implements Rule
{
    private \Closure $valueNormalizer;

    public function __construct(\Closure $valueNormalizer)
    {
        $this->valueNormalizer = $valueNormalizer;
    }

    public static function nameToDomainNormalizer(): \Closure
    {
        return function ($value) {
            // To lower case
            $value = mb_strtolower($value);
            // Space to dash
            $value = str_replace(' ', '-', $value);
            // Remove special chars
            $value = preg_replace('/[^A-Za-z0-9\-]/', '', $value);
            // Remove double dash
            $value = str_replace('--', '-', $value);

            // Remove dash at the end
            return trim($value, '-');
        };
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = ($this->valueNormalizer)($value);

        return ReservedDomain::whereSubdomain($value)->count() === 0
            && Domain::whereDomain($value)->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is already in use. Please try another one.';
    }
}
