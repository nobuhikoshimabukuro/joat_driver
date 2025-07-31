// 引数は操作制御したいセレクタ
function start_loader(target){

  // 処理中のローディングcss
  let Html = '<div class="loader-area">';
  Html += '<div class="loader"></div>';
  Html += '</div>'; 

  // 対象要素に作成したhtmlを追加
  $(Html).appendTo(target); 

}



function end_loader() {

  var elements = document.querySelectorAll('.loader-area');

  // 取得した要素を削除
  elements.forEach(function(element) {
    element.remove();
  });


  var elements = document.querySelectorAll('.loader');

  // 取得した要素を削除
  elements.forEach(function(element) {
    element.remove();
  });
}

function clear_error_message(target){

  $(target).html("");    
  $('.is-invalid').removeClass('is-invalid');
  $('.invalid-feedback').removeClass('invalid-feedback');
      
}


function standby_processing(process_branch ,button ,target = 'body'){

  if(process_branch == 1){

    button.prop("disabled", true);
    document.body.style.cursor = 'wait';

    // 処理中のローディングcss
    let Html = '<div class="processing-area">';
    Html += '<div class="processing"></div>';
    Html += '</div>';

    // 対象要素に作成したhtmlを追加
    $(Html).appendTo(target);


  }else{

    button.prop("disabled", false);
    document.body.style.cursor = 'auto';

    var elements = document.querySelectorAll('.processing-area');

    // 取得した要素を削除
    elements.forEach(function(element) {
      element.remove();
    });
  
  
    var elements = document.querySelectorAll('.processing');
  
    // 取得した要素を削除
    elements.forEach(function(element) {
      element.remove();
    });

  }
  
}

$(document).on("click", ".page-transition-button", function (e) {

  var process = $(this).data('process');
  var url = $(this).data('url');
  if(process == 1){
    window.location.href = url;
  }else if(process == 2){
    window.open(url, '_blank');    
  }else if(process == 3){
    var button = $(this);
    set_session_transition(button);    
  }  

});


function set_session_transition(button) {

  var set_session_url = button.data('set_session_url');
  var transition_url = button.data('transition_url');
  var session_key = button.data('session_key');
  var session_value = button.data('session_value'); 

  standby_processing(1 , button);
  $.ajax({
      url: set_session_url, // 送信先
      type: 'get',
      dataType: 'json',
      data: {session_key : session_key , session_value : session_value},
      // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  })
  .done(function (data, textStatus, jqXHR) {

    standby_processing(2 , button);
    window.location.href = transition_url;

  })
  .fail(function (data, textStatus, errorThrown) {
     
    standby_processing(2 , button);
    window.location.href = transition_url;

  });
}

//モーダルを開いた時の共通イベント
$('.modal').on('show.bs.modal',function(e){  
  $('body').css('overflow-y', 'none');
});

//モーダルを閉じた時の共通イベント
$('.modal').on('hidden.bs.modal', function() {
  $('body').css('overflow-y', 'auto');
});


$(document).on("click", ".common-search-button", function (e) {

  var button = $(this);

  standby_processing(1,button,"body");

  var add_url = "";

  // search-areaを取得
  var search_area = $(".search-area");

  // search_area内のinput, select, textareaを取得
  var search_inputs = search_area.find('input, select, textarea');

  add_url += "?search_process=1";

  // 各要素のnameと値を取得してオブジェクトに追加
  search_inputs.each(function (index) {

      var input_name = $(this).data("target");
      var input_value = $(this).val().trim();
      
      // ラジオボタンの場合、選択された値を取得
      if ($(this).is(":radio")) {

          if($(this).is(":checked")) {
          input_value = $(this).val().trim();
          }else{
          input_value = "";
          }    
      }

      // チェックボックスの場合、選択された値を取得
      if ($(this).is(":checkbox")) {

        if($(this).is(":checked")) {
          input_value = $(this).val().trim();
        }else{
          input_value = "";
        }    
      }

      // numericクラスが存在し、input_valueにカンマが含まれている場合、カンマを除去
      if($(this).hasClass("numeric")){
          input_value = input_value.replace(/,/g, "");
      }

      if (input_value != null && input_value != "") {     

        add_url += "&" + input_name + "=" + input_value;          
      }
  });


  standby_processing(2,button,"body");

  var current_url = window.location.href;

  // URLからクエリパラメータを取り除く
  var current_url = current_url.split('?')[0];

  // 新しいURLを作成
  var new_url = current_url + add_url;

  // ページを新しいURLでリロード
  window.location.href = new_url;  

});

$(document).on("click", ".common-clear-button", function (e) {

 
  var current_url = window.location.href;

  // URLからクエリパラメータを取り除く
  var current_url = current_url.split('?')[0];

  // 新しいURLを作成
  var new_url = current_url;

  // ページを新しいURLでリロード
  window.location.href = new_url;  

});

//Enterキーフォーカス移動 エンターキー
$(document).on("keydown", "input, select", function (e) { 

  var code = e.which ? e.which : e.keyCode;

    if (code == 13) {

      if (e.ctrlKey) {
        
        // Ctrlキーが同時に押されている場合はフォームをサブミット      
        // $(this).closest('form').submit();    

      } else {

        // body内の指定要素を取得
        var fields = $(this).closest('body').find('input, select, textarea');
        // var fields = $(this).closest('body').find('input, select, textarea,button');
        var total = fields.length;
        var index = fields.index(this);

        // ループして次のフォーカス対象を見つける
        for (var i = index + 1; i < total; i++) {

          // // 特定のクラスがある場合かつdisabledでない場合にフォーカスを移動
          // if (!fields.eq(i).hasClass("d-none") && !fields.eq(i).is(":disabled")) {
          //   fields.eq(i).focus();
          //   break;
          // }

          var target = fields.eq(i);
          // 条件: 
          // .d-none を持つ親要素がある場合（スキップされる）
          // 親要素のクラス等の影響で自身が非活性、非表示状態（スキップされる）
          //  original-readonly は プルダウンの非活性
          if (!target.closest('.d-none').length && target.is(":visible") && !target.is(":disabled") && !target.hasClass('original-readonly')) {
            if (target.is(":checkbox")) {
              setTimeout(() => target.focus(), 0); // チェックボックスのフォーカスを遅延適用 
            } else {
              target.focus();
            }
            break;
          }


        }

        return false;

      }

    }
  
});


// Nguyen 20231218 修正 IME変換中を考慮
// 入力時に数字以外の入力値を削除 
$(document).on("input compositionstart compositionend", ".numeric", function (e) {

  if (e.type === "compositionstart") {
    $(this).data("composition", true);
    return;
  }

  // 初期値設定
  var target_val;

    // 'hyphen' クラスが存在するかチェック
    if ($(this).hasClass('hyphen')) {
      // ハイフン 郵便番号や電話番号など
      target_val = /[^0-9０-９ー-]/g;  // 半角・全角数字、ハイフン（-）と全角マイナス記号（ー）を許容

    } else if ($(this).hasClass('weight') || $(this).hasClass('corporate_no')) {
      // 重さ
      target_val = /[^\d０-９.]/g;  // 半角・全角数字のみ許容

    } else {
      // その他 金額など
      target_val = /[^\d０-９,]/g;  // 半角・全角数字、カンマのみ許容

    }


  if (e.type === "compositionend") {
    $(this).data("composition", false);
    // 変換完了時に不要な文字を削除
    var inputValue = $(this).val();
    var sanitizedValue = inputValue.replace(target_val, ""); // 文字を削除

    if (inputValue !== sanitizedValue) {
      $(this).val(sanitizedValue);
    }
    return;
  }

  if ($(this).data("composition")) {
    return;
  }

  var inputValue = $(this).val();
  var sanitizedValue = inputValue.replace(target_val, ""); // 文字を削除

  if (inputValue !== sanitizedValue) {
    $(this).val(sanitizedValue);
  }
});

// input typeにフォーカスがあたった場合、全選択する
$(document).on("focus", "input, textarea", function (e) {
    $(this).select();
});

// フォーカスでカンマを削除
$(document).on("focus", ".numeric", function (e) {  
  var num = $(this).val().replace(/,/g, '');
  $(this).val(num);
  $(this).select();
});

// フォーカスアウトでカンマを挿入
$(document).on("blur", ".numeric", function (e) {  
  
  // 入力値を取得
  var numString = $(this).val();

  // 全角数字、全角小数点、全角英字を半角に変換
  numString = fullToHalf(numString);

  // 入力が数字でない場合、何も入力されていない場合
  if (!numString || isNaN(numString) || $(this).hasClass('hyphen') || $(this).hasClass('weight') || $(this).hasClass('corporate_no')) {
    // 処理中断
    return;
  }
    
  // 小数点以下が存在する場合としない場合で分岐
  var hasDecimal = numString.indexOf('.') !== -1;

  // 先頭のゼロを無視して数字に変換
  var num = hasDecimal ? parseFloat(numString) : parseInt(numString, 10);

  // 数字に変換後の値を文字列に変換
  var numAsString = num.toString();

  // 変換後の文字列を使用して整数部と小数部に分割
  var parts = numAsString.split('.');
  
  // 整数部を3桁ごとにカンマ挿入
  var integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

  // 小数部が存在する場合は整数部と結合し、フォーマット
  var formattedNumber = parts.length > 1 ? integerPart + '.' + parts[1] : integerPart;
  
  // フォーマットされた数値をフィールドに設定
  $(this).val(formattedNumber);
});



// 全角数字、全角小数点、全角英字を半角に変換する関数
function fullToHalf(input) {
  return input.replace(/[０-９．Ａ-Ｚａ-ｚ]/g, function(s) {
    // 文字コードの変換
    return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
  });
}



// モーダルが開いたら、モーダル内の入力項目にフォーカスを当てる。
$(document).ready(function() {
  $('#save-modal').on('shown.bs.modal', function () {
    let modal = $(this);

    // 非同期で aria-hidden を削除
    setTimeout(() => {
      modal.removeAttr('aria-hidden');
    }, 10);

    let targets = modal.find('input:visible:not(:disabled):not(.original-readonly), select:visible:not(:disabled):not(.original-readonly), textarea:visible:not(:disabled):not(.original-readonly)');

    let target = null;

    targets.each(function () {
        let current = $(this);
        if (current.closest('.d-none').length === 0) {
            target = current;
            return false; // ループを抜ける
        }
    });

    if (target) {
        if (target.is(":checkbox")) {
            setTimeout(() => target.focus(), 0);
        } else {
            target.focus();
        }
    }
  });
});

//インフォメーションモーダル表示
function show_login_info_modal(message) {

  // すべてのモーダルを非表示にする
  $(".modal").modal('hide');

  // メッセージを表示するエリアにメッセージをセット
  $(".info_message_area").html(message);
  
   // 再ログインモーダルを表示する
  $("#info-modal").modal('show');

  
}


//session切れ場合に再ログインを促すモーダル表示
function show_login_again_modal() {

  // すべてのモーダルを非表示にする
  $(".modal").modal('hide');

   // 再ログインモーダルを表示する
  $("#login_again-modal").modal('show');

  
}


// 郵便番号検索の共通関数
async function searchAndFillAddress(postCodeInput, addressFields) {
  // バリデーション: 郵便番号の入力チェック
  postCodeInput.removeClass('is-invalid');
  var post_code = postCodeInput.val();

  if (post_code === "") {
      postCodeInput.addClass('is-invalid');
      return;
  }

  try {
      var address_info = await search_address_api(post_code);

      if (address_info.address1) {
          var fullAddress = address_info.address1 + address_info.address2 + address_info.address3;

          // 現在の入力値を取得
          var currentAddress = $(addressFields.address1).val();

          // 既に住所が入力されている場合、上書き確認
          if (currentAddress) {
              var userConfirmed = confirm("住所項目が入力されています。\n上書きしてもよろしいですか？");
              if (!userConfirmed) {
                  return; // キャンセルなら処理を中断
              }
          }

          // 住所を入力
          $(addressFields.address1).val(fullAddress);
      } else {
          console.log("住所が見つかりませんでした");
      }

  } catch (error) {
      console.error("APIリクエストエラー:", error);
  }
}





/**************************
  住所取得後表示処理
  post_code(検索する郵便番号)    
***************************/
function search_address_api(post_code) {
  
  return new Promise((resolve, reject) => {
      // ハイフンを除去
      post_code = post_code.replace(/-/g, '');

      // 数字のみ & 7桁かチェック
      if (!/^\d{7}$/.test(post_code)) {
          resolve([]); // 条件を満たさない場合は空配列を返す
          return;
      }

      var parameter = { zipcode: post_code };
      var zipcloud_url = "https://zipcloud.ibsnet.co.jp/api/search";

      $.ajax({
          type: "GET",
          cache: false,
          data: parameter,
          url: zipcloud_url,
          dataType: "jsonp",
          success: function (retur_value) {

              if (retur_value.status === 200 && retur_value.results !== null) {
                  resolve(retur_value.results[0]); // 最初の住所データを返す
              } else {
                  resolve([]); // 住所が見つからなかった場合
              }

          },
          error: function () {
              reject("APIリクエストエラー");
          }
      });
  });
}





/**
 * 指定された料金表テーブル（jQueryオブジェクト）から、全体のpayloadデータを生成する共通関数
 * @param {jQuery} $table - 対象の料金表テーブル（例：$('#input-table')）
 * @returns {Object} payload - サーバーに送信可能なデータ構造
 */
const getPriceTablePayload = ($table) => {
  // 共通関数：カンマを除去し、数値に変換（空文字はそのまま返す）
  const parseVal = (val) => val === '' ? '' : parseFloat(val.replace(/,/g, ''));

  /**
   * 重量 or 距離の「範囲データ（from/to）」を抽出
   * @param {jQuery} $targets - 対象の <th> 要素群（距離行 or 重量列）
   * @param {Array} keys - プロパティ名（例：['distance_f', 'distance_t']）
   * @returns {Array} 範囲オブジェクトの配列
   */
  const extractRanges = ($targets, keys) => {
      return $targets.map(function () {
          const inputs = $(this).find('input');
          return {
              [keys[0]]: parseVal($(inputs[0]).val()),
              [keys[1]]: parseVal($(inputs[1]).val())
          };
      }).get();
  };

  /**
   * 価格データを row/col 単位で抽出
   * @param {string} field - 対象の data-field 属性（price_normal / price_terminal）
   * @returns {Array} 価格オブジェクトの配列（row, col付き）
   */
  const extractPrices = (field) => {
      const result = [];
      $table.find('tbody tr').each(function (rowIndex) {
          $(this).find('td').each(function (colIndex) {
              const val = $(this).find(`input[data-field="${field}"]`).val();
              result.push({
                  row: rowIndex,
                  col: colIndex,
                  [field]: parseVal(val)
              });
          });
      });
      return result;
  };

  // 重量（横軸）：先頭2列を除いたヘッダー<th>を取得
  const weightThs = $table.find('thead tr.first_header th:gt(1)');

  // 距離（縦軸）：すべての<tr>行を取得
  const $distanceRows = $table.find('tbody tr');

  // 区域：行ごとの2番目の<th>から取得
  const area_ranges = $distanceRows.map(function () {
      const area = $(this).find('th').eq(1).find('input').val();
      return { area };
  }).get();

  // 明細データ：全距離 × 全重量セルの金額・範囲をまとめる
  const meisai_data = [];
  $distanceRows.each(function (rowIndex) {
      const $th = $(this).find('th');
      const distance_f = $th.find('input').eq(0).val();
      const distance_t = $th.find('input').eq(1).val();
      const area = $th.eq(1).find('input').val();

      weightThs.each(function (colIndex) {
          const weightInputs = $(this).find('input');
          const weight_f = $(weightInputs[0]).val();
          const weight_t = $(weightInputs[1]).val();

          // 対応する金額セル（td）を取得
          const $td = $table.find('tbody tr').eq(rowIndex).find('td').eq(colIndex);

          meisai_data.push({
              distance_f, distance_t, area,
              weight_f, weight_t,
              price_normal: $td.find('input[data-field="price_normal"]').val(),
              price_terminal: $td.find('input[data-field="price_terminal"]').val()
          });
      });
  });

  // カスタム情報（テーブル外部の基本情報フォーム）
  const price_custom_info_data = {
      price_custom_cd: $('#price_custom_cd').val(),
      price_custom_name: $('#price_custom_name').val(),
      remarks: $('#remarks').val()
  };

  // 最終的な payload オブジェクトを返却
  return {
      price_custom_info_data,
      meisai_data,
      weight_ranges: extractRanges(weightThs, ['weight_f', 'weight_t']),
      distance_ranges: extractRanges($distanceRows, ['distance_f', 'distance_t']),
      area_ranges,
      price_normal_ranges: extractPrices('price_normal'),
      price_terminal_ranges: extractPrices('price_terminal')
  };
};

// FSI.Nguyen 20250604 作成 試験中。検索項目内で、最後のフィールドでエンターおされたら、検索ボタンを疑似クリックする
// これにより、ユーザーが検索ボタンをクリックせずにエンターキーで検索を実行できるようになります。
$(document).ready(function() {
  // 見えているinputフィールドとselectフィールドを探し、最後のものを取得
  $('.search-area .search-table input:visible, .search-area .search-table select:visible').last().on('keydown', function(event) {
      if (event.key === 'Enter') {
          // エンターキーが押されたinputまたはselect要素
          var inputField = $(this);

          // 最後のinput/select要素が含まれるtr要素を親として、最も近い検索ボタンを選択
          var closestButton = inputField.closest('tr').find('.common-search-button');

          // 検索ボタンが見つかった場合にクリック
          if (closestButton.length) {
              closestButton.click();
          }
      }
  });
});

// 2025/06/25
// 郵便番号入力
$(document).on({
  // フォーカス時
  focus: function () {
      let zip = $(this).val().replace(/-/g, '');
      $(this).val(zip);
  },
  // フォーカスアウト時
  blur: function () {
      let zip = $(this).val().replace(/[^0-9]/g, '');
      if (zip.length === 7) {
          $(this).val(zip.slice(0, 3) + '-' + zip.slice(3));
      }
  }
}, ".post_number");