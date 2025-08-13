<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Original\Manager\ManagerCommon;

class ManagerSessionConfirmationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // セッション情報取得
            $session_info = ManagerCommon::GetManagerUserInfo();

            // セッション有無
            if (!$session_info->login_status) {
                $validator->errors()->add("login_again", '');
            }
        });
    }
}
