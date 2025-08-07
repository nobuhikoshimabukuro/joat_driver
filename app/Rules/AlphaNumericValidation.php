<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 *  第一引数 1 = 半角英数字  2 = 全角英数字 3 = 半角全角英数字
 *  第二引数 禁止する文字配列 例：["l","o"]。初期値は[]
**/
class AlphaNumericValidation implements Rule
{
    protected $mode;
    protected $charMap;
    protected $excludeChars;
    protected $invalidChars = [];

    public function __construct($mode = 1, array $excludeChars = [])
    {
        $this->mode = $mode;
        $this->excludeChars = $excludeChars;

        // 許可する文字リスト（手動リスト）
        $this->charMap = [
            1 => [ // 半角英数字（手動リスト）
                'a','b','c','d','e','f','g','h','i','j','k','l',
                'm','n','o','p','q','r','s','t','u','v','w','x','y','z',
                'A','B','C','D','E','F','G','H','I','J','K',
                'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                '0','1','2','3','4','5','6','7','8','9'
            ],
            2 => [ // 全角英数字（手動リスト）
                'Ａ','Ｂ','Ｃ','Ｄ','Ｅ','Ｆ','Ｇ','Ｈ','Ｉ','Ｊ','Ｋ','Ｌ','Ｍ','Ｎ','Ｏ','Ｐ','Ｑ','Ｒ','Ｓ','Ｔ','Ｕ','Ｖ','Ｗ','Ｘ','Ｙ','Ｚ',
                'ａ','ｂ','ｃ','ｄ','ｅ','ｆ','ｇ','ｈ','ｉ','ｊ','ｋ','ｌ','ｍ','ｎ','ｏ','ｐ','ｑ','ｒ','ｓ','ｔ','ｕ','ｖ','ｗ','ｘ','ｙ','ｚ',
                '０','１','２','３','４','５','６','７','８','９'
            ],
            3 => [ // 半角 + 全角英数字（手動リスト）
                'a','b','c','d','e','f','g','h','i','j','k','l',
                'm','n','o','p','q','r','s','t','u','v','w','x','y','z',
                'A','B','C','D','E','F','G','H','I','J','K',
                'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                '0','1','2','3','4','5','6','7','8','9',
                'Ａ','Ｂ','Ｃ','Ｄ','Ｅ','Ｆ','Ｇ','Ｈ','Ｉ','Ｊ','Ｋ','Ｌ','Ｍ','Ｎ','Ｏ','Ｐ','Ｑ','Ｒ','Ｓ','Ｔ','Ｕ','Ｖ','Ｗ','Ｘ','Ｙ','Ｚ',
                'ａ','ｂ','ｃ','ｄ','ｅ','ｆ','ｇ','ｈ','ｉ','ｊ','ｋ','ｌ','ｍ','ｎ','ｏ','ｐ','ｑ','ｒ','ｓ','ｔ','ｕ','ｖ','ｗ','ｘ','ｙ','ｚ',
                '０','１','２','３','４','５','６','７','８','９'
            ]
        ];
    }

    public function passes($attribute, $value)
    {
        if (!isset($this->charMap[$this->mode])) {
            return false; // 不正なモードなら即NG
        }

        $this->invalidChars = []; // 毎回リセット
        $allowedChars = array_diff($this->charMap[$this->mode], $this->excludeChars); // 除外リスト適用

        foreach (mb_str_split($value) as $char) {
            if (!in_array($char, $allowedChars)) {
                $this->invalidChars[] = $char; // 入力値の中で使用不可の文字のみ記録
            }
        }

        return empty($this->invalidChars); // 使用不可の文字がなければOK
    }

    public function message()
    {
        if (!empty($this->invalidChars)) {
            $invalidList = implode(', ', array_unique($this->invalidChars));
            return ":attributeは指定された形式で入力してください。（使用不可の文字: {$invalidList}）";
        }

        return ":attributeは指定された形式で入力してください。";
    }
}
