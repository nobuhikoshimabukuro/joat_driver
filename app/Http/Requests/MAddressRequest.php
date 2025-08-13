<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Original\Manager\ManagerCommon;
use League\Csv\Reader;

class MAddressRequest extends FormRequest
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

            $session_info = ManagerCommon::GetManagerUserInfo();                    

            // セッション有無
            if ($session_info->login_status) {            
                
                // CSVファイルを取得
                $normal_csv = $this->file('normal_csv');
                $jigyosyo_csv = $this->file('jigyosyo_csv');

                if (!$normal_csv) {
                    $validator->errors()->add('normal_csv', '都道府県CSVファイルが選択されていません。');                     
                }else{


                    // CSVを読み込む
                    $csv = Reader::createFromPath($normal_csv->getPathname(), 'r');
                    $records = $csv->getRecords();

                    // 必須の列数（規格に合わせる）
                    $expectedColumnCount = 15;

                    // 最初の1行だけ確認
                    $firstRow = null;
                    foreach ($records as $data) {
                        $firstRow = $data;
                        break; // すぐに抜ける
                    }

                    if (!$firstRow) {
                        $validator->errors()->add('normal_csv', 'CSVファイルが空です。');                        
                    }

                    // 列数チェック
                    if (count($firstRow) !== $expectedColumnCount) {
                        $validator->errors()->add('normal_csv', "都道府県CSVの規格が正しくありません（{$expectedColumnCount}列必要）");                        
                    }
                

                }

                if (!$jigyosyo_csv) {
                    $validator->errors()->add('jigyosyo_csv', '事業所CSVファイルが選択されていません。');                     
                }else{


                    // CSVを読み込む
                    $csv = Reader::createFromPath($jigyosyo_csv->getPathname(), 'r');
                    $records = $csv->getRecords();

                    // 必須の列数（規格に合わせる）
                    $expectedColumnCount = 13;

                    // 最初の1行だけ確認
                    $firstRow = null;
                    foreach ($records as $data) {
                        $firstRow = $data;
                        break; // すぐに抜ける
                    }

                    if (!$firstRow) {
                        $validator->errors()->add('jigyosyo_csv', '事業所CSVファイルが空です。');                        
                    }

                    // 列数チェック
                    if (count($firstRow) !== $expectedColumnCount) {
                        $validator->errors()->add('jigyosyo_csv', "事業所CSVの規格が正しくありません（{$expectedColumnCount}列必要）");                        
                    }
                

                }


            }else{
                $validator->errors()->add("login_again", '');
            }
        });
    }
}
