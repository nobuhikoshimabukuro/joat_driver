<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;


/**
 *  3桁-4桁 または 3桁4桁のみ 
**/
class PostalCodeValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        
        // 郵便番号のフォーマットをチェック（例: 123-4567 または 1234567）
        return preg_match('/^\d{3}-?\d{4}$/', $value);
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attributeを確認してください。";
    }
}
