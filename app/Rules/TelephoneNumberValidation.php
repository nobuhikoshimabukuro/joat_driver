<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TelephoneNumberValidation implements Rule
{
    protected $message;

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
        // 半角数字とハイフンのみを許可する正規表現
        if (!preg_match('/^[0-9\-]+$/', $value)) {
            $this->message = ":attributeは半角数字とハイフン(-)で入力してください。";
            return false; // 半角数字とハイフン以外の文字が含まれている場合
        }

        // ハイフンが含まれているかチェック
        $hasHyphen = strpos($value, '-') !== false;

        // ハイフンが含まれている場合、最大15文字
        if ($hasHyphen && strlen($value) > 15) {
            $this->message = ":attributeの形式を確認してください。";
            return false;
        }

        // ハイフンが含まれていない場合、最大13文字
        if (!$hasHyphen && strlen($value) > 13) {
            $this->message =  ":attributeの形式を確認してください。";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
