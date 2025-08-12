<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Rules ↓
//郵便番号番号チェック
use App\Rules\PostalCodeValidation;
//カタカナチェック
use App\Rules\KatakanaValidation;
//電話番号チェック
use App\Rules\TelephoneNumberValidation;
// 半角英数字
use App\Rules\AlphaNumericValidation;
// Rules ↑

class MLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules(): array
	{
		return [
			'license_name' => 'required',
            'license_name_kana' => ['nullable', 'string', 'max:100', new KatakanaValidation(2)],                 
			'remarks' => 'nullable|string|max:500',
		];
	}

    public function attributes()
    {
        return [            
            'license_name' => '資格・免許名',
            'license_name_kana' => '資格・免許名_カナ',           
            'remarks' => '備考',            
        ];
    }
    

	public function withValidator($validator)
	{
		$validator->after(function ($validator) {

            if(1 == 1){

            }else{
                $validator->errors()->add("login_again", '');
            }
			
		});
	}
}
