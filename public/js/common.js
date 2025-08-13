function EndLoader() {

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


function StandbyProcessing(process_branch ,button ,target = 'body'){

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

  StandbyProcessing(1 , button);
  $.ajax({
      url: set_session_url, // 送信先
      type: 'get',
      dataType: 'json',
      data: {session_key : session_key , session_value : session_value},
      // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  })
  .done(function (data, textStatus, jqXHR) {

    StandbyProcessing(2 , button);
    window.location.href = transition_url;

  })
  .fail(function (data, textStatus, errorThrown) {
     
    StandbyProcessing(2 , button);
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

  StandbyProcessing(1,button,"body");

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


  StandbyProcessing(2,button,"body");

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
  if (!numString || isNaN(numString) || $(this).hasClass('hyphen') || $(this).hasClass('weight') || $(this).hasClass('corporate_number')) {
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




function ErrorClear(buttonName , targetArea ) {
  
  $('.is-invalid').removeClass('is-invalid');

  if(buttonName != ""){
    $(buttonName).addClass('d-none');
  }

  if(targetArea != ""){
    $(targetArea).empty();
  }  
}


function AddInvalid(ErrorElement) {
  
  // IDがあるか確認
  if ($(`#${ErrorElement}`).length > 0) {
      $(`#${ErrorElement}`).addClass('is-invalid');
  }
  // IDが無くて name がある場合
  else if ($(`[name="${ErrorElement}"]`).length > 0) {
      $(`[name="${ErrorElement}"]`).addClass('is-invalid');
  }
  
}

function ShowErrorModal(element , target = "" , buttonName = "") {
  
  // すべてのモーダルを非表示にする
  $(".modal").modal('hide');
  
  // メッセージを表示する要素追加
  if(target != ""){
    $(element).appendTo(target);
  }     

  if(buttonName != ""){
    $(buttonName).removeClass('d-none');
  }

  // エラーモーダルを表示する
  var Modal = new bootstrap.Modal(document.getElementById('error-modal'), {
    backdrop: 'static',
    keyboard: false
  });
  Modal.show();  

  
}

$(document).on("click", ".error_focus_button", function (e) {

  $("#error-modal").modal('hide');

  let button = $(this);
  var target = button.data('target');

  let $targetElement = $(`#${target}`);
  if ($targetElement.length === 0) {
    $targetElement = $(`[name="${target}"]`);
  }

  if ($targetElement.length > 0) {
    // フォーカスを当てる
    $targetElement.focus();

    // スクロール位置を計算して調整（画面の中心に持ってくる）
    let elementOffset = $targetElement.offset().top;
    let elementHeight = $targetElement.outerHeight();
    let windowHeight = $(window).height();

    // 要素が画面中央にくるようにスクロール位置を計算
    let scrollTo = elementOffset - (windowHeight / 2) + (elementHeight / 2);

    // スクロールアニメーション（スマホも対応）
    $('html, body').animate({ scrollTop: scrollTo }, 500);
  }

});


$(document).on("click", ".error_confirmation_button", function (e) {

  var Modal = new bootstrap.Modal(document.getElementById('error-modal'), {
    backdrop: 'static',
    keyboard: false
  });
  Modal.show();  
  
});


//session切れ場合に再ログインを促すモーダル表示
function ShowLoginAgainModal() {

  // すべてのモーダルを非表示にする
  $(".modal").modal('hide');

  // 再ログインモーダルを表示する
  var Modal = new bootstrap.Modal(document.getElementById('login_again-modal'), {
    backdrop: 'static',
    keyboard: false
  });
  Modal.show();

  
}


// モーダル要素取得
const infoModal = document.getElementById('info-modal');

// モーダルインスタンス作成（既に作っていたらそのままでOK）
const modalInstance = new bootstrap.Modal(infoModal, {
  backdrop: 'static',
  keyboard: false
});

function DataUpdateSelectRow(completion_info) {
  var targetId = completion_info && completion_info.target_row_id ? completion_info.target_row_id : null;

  if (targetId) {
    const targetRow = document.querySelector(`[data-target_row="${targetId}"]`);
    if (targetRow) {
      targetRow.scrollIntoView({behavior: 'smooth', block: 'center'});
      targetRow.classList.add('highlight-flash');
    }
  }

  if (completion_info && completion_info.message) {
    document.querySelector('.info_message_area').innerHTML = completion_info.message;
  }

  modalInstance.show();
}

// モーダルが閉じた時のイベント
infoModal.addEventListener('hidden.bs.modal', () => {
  const highlighted = document.querySelector('.highlight-flash');
  if (highlighted) {
    highlighted.classList.remove('highlight-flash');
  }
});





