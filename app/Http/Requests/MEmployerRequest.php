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

class MEmployerRequest extends FormRequest
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
			'employer_category' => 'required',
            'employer_name' => 'required|string|max:100',
            'employer_name_kana' => ['nullable', 'string', 'max:100', new KatakanaValidation(2)],
			'postal_code' => ['nullable', 'string', 'max:8', new PostalCodeValidation()],			
            'address1' => 'required|string|max:100',
			'address2' => 'nullable|string|max:100',
			'address3' => 'nullable|string|max:100',			
			// 'tel1' => ['nullable', new TelephoneNumberValidation()],
            // 'tel2' => ['nullable', new TelephoneNumberValidation()],
            // 'fax1' => ['nullable', new TelephoneNumberValidation()],
            // 'fax2' => ['nullable', new TelephoneNumberValidation()],			
            'mailaddress' => 'nullable|email|max:100',
			'remarks' => 'nullable|string|max:500',
		];
	}

    public function attributes()
    {
        return [            
            'employer_category' => '求人元区分',
            'corporate_number' => '法人番号',
            'employer_name' => '求人元名',
            'employer_name_kana' => '求人元名（カナ）',
            'postal_code' => '郵便番号',
            'address1' => '住所1',
            'address2' => '住所2',
            'address3' => '住所3',
            'tel1' => '電話番号1',
            'tel2' => '電話番号2',
            'fax1' => 'FAX1',
            'fax2' => 'FAX2',
            'mailaddress' => 'メールアドレス',
            'remarks' => '備考',            
        ];
    }
    

	public function withValidator($validator)
	{
		$validator->after(function ($validator) {

			
		});
	}
}
