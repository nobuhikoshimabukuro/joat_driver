<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 *  引数 1 = 半角カナ  2 = 全角カナ 3 = 半角全角カナ
**/
class KatakanaValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    //$ProcessingTy 1 = 半角カナ  2 = 全角カナ 3 = 半角全角カナ
    public function __construct(private int $ProcessingType)
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
        if ($this->ProcessingType == 1) {
            // 半角の伸ばし棒「ｰ」のみ許容
            return preg_match('/^[ｦ-ﾟｰ 　]+$/u', $value);
        } else if ($this->ProcessingType == 2) {
            // 全角の伸ばし棒「ー」許容
            return preg_match('/^[ア-ン゛゜ァ-ォャ-ョーｦ-ﾟ　ー ]+$/u', $value);
        } else {
            // ProcessingTypeが3の場合やその他、全角と半角の伸ばし棒「ー」「ｰ」両方許容
            return preg_match('/^[ァ-ヶｦ-ﾟア-ン゛゜ 　ーｰ]+$/u', $value);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->ProcessingType == 1){
            return ":attributeは半角カナで入力してください。";
        }else if($this->ProcessingType == 2){
            return ":attributeは全角カナで入力してください。";
        }else{
            return ":attributeはカタカナで入力してください。";            
        }
    }
}
