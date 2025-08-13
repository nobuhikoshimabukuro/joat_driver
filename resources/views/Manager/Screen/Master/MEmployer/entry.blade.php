@php
  $title = "求人元情報編集";
@endphp

@extends('Manager.Common.layouts_app')

@section('title',  $title)


@section('pagestyle')
<!-- 画面別CSS -->
<style>  


  .corporate_number_area
   {
    display: none; /* 初期状態で非表示にする */
  }

</style>
@endsection

{{-- メインエリア --}}
@section('content')  

<div class="row align-items-center mb-3">
  <div class="col-auto">
    <h4 class="mb-0">{{$title}}</h4>
  </div>  
</div>


<form id='save-form' class="form-area" action="{{ route('manager.master.m_employer.save') }}" method="post" enctype="multipart/form-data">
  @csrf
  
  <input type="hidden" id="employer_id" name="employer_id" value="{{$m_employer->employer_id}}">

  <div class="row m-0 p-1">

    <div class="col-xxl-2 col-xl-3 col-lg-6 col-12 p-1 m-0">

      <label for="employer_cd" class="form-label">ログインCD</label>
      <input type="text" id="employer_cd" name="employer_cd"
      class="form-control" 
      value="{{$m_employer->employer_cd}}"
      >

    </div>

    <div class="col-xxl-2 col-xl-1 col-lg-6 col-12 p-1 m-0">

    </div>

    <div class="col-xxl-1 col-xl-2 col-lg-3 col-5 p-1 m-0">      

      <label for="employer_category" class="form-label">求人元区分</label>
      <select id="employer_category" name="employer_category" class='form-control w-auto' data-target="corporate_number_area">    
        <option value="0">未設定</option>
            @foreach($employer_categories as $item)
                    <option value="{{$item->value}}"
                    @if($m_employer->employer_category == $item->value) selected @endif
                    >
                    {{$item->display}}
                    </option>
            @endforeach
        </select>
    </div>

    <div class="col-xxl-3 col-xl-3 col-lg-6 col-7 p-1 m-0 d-none" id="corporate_number_area" >
      <label for="corporate_number" class="form-label">法人番号</label>
      <input type="text" id="corporate_number" name="corporate_number"
      class="form-control text-end w-160px" 
      value="{{$m_employer->corporate_number}}"
      maxlength="13"
      >

    </div>

    

  </div>
  

  <div id="" class="row m-0 p-1">    
    <div class="col-xxl-4 col-xl-4 col-lg-6 col-12 p-1 m-0">

      <label for="employer_name" class="form-label">求人元名</label>
      <input type="text" id="employer_name" name="employer_name"
      class="form-control" 
      value="{{$m_employer->employer_name}}"
      >

    </div>

    <div class="col-xxl-4 col-xl-4 col-lg-6 col-12 p-1 m-0">

        <label for="employer_name_kana" class="form-label">求人元名カナ</label>
        <input type="text" id="employer_name_kana" name="employer_name_kana"
        class="form-control" 
        value="{{$m_employer->employer_name_kana}}"        
        >     
    </div>   


  </div>

  <div id="" class="row m-0 p-1">    

    @php
      $postal_code = $m_employer->postal_code;
      $postal_code_front = "";
      $postal_code_back = "";

      // 7桁の数字チェック
      if (preg_match('/^\d{7}$/', $postal_code)) {
          $postal_code_front = substr($postal_code, 0, 3);
          $postal_code_back = substr($postal_code, 3, 4);
      }
    @endphp
    <div class="col-12 p-1 m-0 ">
      <label for="postal_code_front" class="form-label">郵便番号</label>

      <div class="m-0 p-0 d-flex">
        <input type="text" id="postal_code_front" name="postal_code_front"
        class="form-control text-end w-80px" 
        value="{{$postal_code_front}}"
        maxlength="3"
        >

        <div class="px-1 d-flex align-items-center">―</div>

        <input type="text" id="postal_code_back" name="postal_code_back"
        class="form-control text-end w-90px" 
        value="{{$postal_code_back}}"
        maxlength="4"
        >

        <button type="button" class="btn btn-outline-primary ms-2 search_address_button">住所検索</button>
        
      </div>

    </div>

  </div>



  <div id="" class="row m-0 p-1">    
    <div class="col-xxl-4 col-xl-4 col-12 p-1 m-0">

      <label for="address1" class="form-label">住所1</label>
      <button type="button" class="btn btn-outline-primary ms-2 search_postal_code_button">郵便番号検索</button>
      <input type="text" id="address1" name="address1"
      class="form-control" 
      value="{{$m_employer->address1}}"
      >

    </div>

    <div class="col-xxl-4 col-xl-4 col-12 p-1 m-0">

      <label for="address2" class="form-label">住所2</label>
      <input type="text" id="address2" name="address2"
      class="form-control" 
      value="{{$m_employer->address2}}"
      >
    </div>   

    <div class="col-xxl-4 col-xl-4 col-12 p-1 m-0">

      <label for="address3" class="form-label">住所3</label>
      <input type="text" id="address3" name="address3"
      class="form-control" 
      value="{{$m_employer->address3}}"
      >
    </div>   


  </div>


  <div id="" class="row m-0 p-1">

    <div class="col-xxl-2 col-xl-3 col-6 p-1 m-0">

      <label for="tel1" class="form-label">電話番号1</label>      
      <input type="text" id="tel1" name="tel1"
      class="form-control w-170px" 
      value="{{$m_employer->tel1}}"
      maxlength="16"
      >

    </div>

    <div class="col-xxl-2 col-xl-3 col-6 p-1 m-0">

      <label for="tel2" class="form-label">電話番号2</label>      
      <input type="text" id="tel2" name="tel2"
      class="form-control w-170px" 
      value="{{$m_employer->TEL2}}"
      maxlength="16"
      >
    </div>

    <div class="col-xxl-2 col-xl-3 col-6 p-1 m-0">

      <label for="fax1" class="form-label">FAX1</label>      
      <input type="text" id="fax1" name="fax1"
      class="form-control w-170px" 
      value="{{$m_employer->fax1}}"
      maxlength="16"
      >

    </div>

    <div class="col-xxl-2 col-xl-3 col-6 p-1 m-0">

      <label for="fax2" class="form-label">FAX2</label>      
      <input type="text" id="fax2" name="fax2"
      class="form-control w-170px" 
      value="{{$m_employer->fax2}}"
      maxlength="16"
      >
    </div>

    <div class="col-xxl-4 col-xl-4 col-lg-12 col-12 p-1 m-0">

      <label for="mailaddress" class="form-label ">メール</label>
      <input type="text" id="mailaddress" name="mailaddress"
      class="form-control" 
      value="{{$m_employer->mailaddress}}"
      >

    </div>   


  </div>

  
  <div id="" class="row m-0 p-1">    
    <div class="col-xxl-6 col-xl-6 col-lg-6 col-12 p-1 m-0">
      <label for="remarks" class="form-label">備考</label>
      <textarea id="remarks" name="remarks" class="form-control" rows="4">{{$m_employer->remarks}}</textarea>
    </div>   
  </div>

  <div id="" class="row m-0 p-1">    
    <div class="col-12 text-center">
      <button id="save-button" class="btn btn-outline-primary ms-1 me-1">登録</button>

      @if($m_employer->employer_id != 0)

        @if(!is_null($m_employer->deleted_at))
          <button type="button" 
          data-process="2"
          data-employer_id="{{$m_employer->employer_id}}"
          class="btn btn-outline-success delete-button ms-1 me-1">削除取消</button>       
        @else

          <button type="button" 
          data-process="1"
          data-employer_id="{{$m_employer->employer_id}}"
          class="btn btn-outline-danger delete-button ms-1 me-1">削除</button>    

        @endif    

      @endif
      
      <button type="button" class="btn btn-outline-danger error_confirmation_button ms-1 me-1 d-none">エラー確認</button>
    </div>
  </div>


</form>


@include('Manager.Common.error_modal')
@include('Manager.Common.login_again_modal')

@endsection
@section('pagejs')  
<script type="text/javascript">
$(document).ready(function () {

  // 法人番号エリアの表示制御（初期＋変更時）
  function toggleCorporateNumberArea() {
    const selectedValue = $('#employer_category').val();
    const $target = $('#corporate_number_area');

    if (selectedValue === '2') {
      $target.removeClass('d-none');
    } else {
      $target.addClass('d-none');
    }
  }

  // ログイン情報エリアの表示制御
  function toggleLoginInfoArea() {
    const isChecked = $('#login_info_change_flg').prop('checked');
    const $target = $('#login_info_area');

    if (isChecked) {
      $target.removeClass('d-none');
    } else {
      $target.addClass('d-none');
    }
  }

  // イベントバインド
  $('#employer_category').on('change', toggleCorporateNumberArea);
  $('#login_info_change_flg').on('change', toggleLoginInfoArea);

  // 初期状態反映
  toggleCorporateNumberArea();
  toggleLoginInfoArea();




  // 郵便番号から住所検索ボタン    
  $(document).on("click", ".search_address_button", function (e) {

    var button = $(this);
    var postal_code_front = $("#postal_code_front").val();
    var postal_code_back = $("#postal_code_back").val();

    var postal_code = (postal_code_front + postal_code_back).trim();
    
    // 郵便番号の形式チェック（数字7桁）
    if (!/^\d{7}$/.test(postal_code)) {
        alert("郵便番号は7桁の数字で入力してください。");
        return; // 処理を中断
    }

    StandbyProcessing(1,button,"body");  

    searchAddressForPostCode(postal_code)
    .then(function (response) {
        if (response && response.address) {

            var currentaddress1 = $("#address1").val();

            // 上書き確認が必要な場合
            if (currentaddress1 && currentaddress1.trim() !== "") {
                if (!confirm("住所1の入力欄に既に値があります。\n上書きしてもよろしいですか？")) {
                    return; // ユーザーが拒否した場合、処理を中断
                }
            }

            // 正しく住所を代入
            $("#address1").val(response.address);

        } else {
            alert("該当する住所が見つかりませんでした。");
        }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX通信エラー:", textStatus, errorThrown);
        console.error("ステータスコード:", jqXHR.status);
        console.error("レスポンス本文:", jqXHR.responseText);

        alert("検索に失敗しました。\n" + 
              "ステータス: " + jqXHR.status + "\n" + 
              "エラー内容: " + errorThrown);
    })
    .always(function () {
        StandbyProcessing(2,button,"body");
    });
  });


  // 住所から郵便番号検索ボタン    
  $(document).on("click", ".search_postal_code_button", function (e) {

    var button = $(this);
    var address1 = $("#address1").val();
    var address2 = $("#address2").val();
    var address3 = $("#address3").val();
    
   

    var address = (address1 + address2 + address3).trim();

    // 入力がない場合はアラート表示して処理中断
    if (address == "") {
        alert("住所を入力してください。");
        return;
    }

    StandbyProcessing(1,button,"body");  

    searchPostalCodeForAddress(address)
    .then(function (response) {
        if (response && response.postal_code) {

          var response_postal_code = response.postal_code;

          var postal_code_front = $("#postal_code_front").val();
          var postal_code_back = $("#postal_code_back").val();

            var currentpostal_code = postal_code_front + postal_code_back;

            // 上書き確認が必要な場合
            if (currentpostal_code && currentpostal_code.trim() !== "") {
                if (!confirm("郵便番号の入力欄に既に値があります。\n上書きしてもよろしいですか？")) {
                    return; // ユーザーが拒否した場合、処理を中断
                }
            }

            // 郵便番号を3桁 + 4桁に分割
            var new_postal_code_front = response_postal_code.substring(0, 3);
            var new_postal_code_back = response_postal_code.substring(3, 7);

            // 正しく代入
            $("#postal_code_front").val(new_postal_code_front);
            $("#postal_code_back").val(new_postal_code_back);

        } else {
            alert("該当する郵便番号が見つかりませんでした。");
        }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX通信エラー:", textStatus, errorThrown);
        console.error("ステータスコード:", jqXHR.status);
        console.error("レスポンス本文:", jqXHR.responseText);

        alert("検索に失敗しました。\n" + 
              "ステータス: " + jqXHR.status + "\n" + 
              "エラー内容: " + errorThrown);
    })
    .always(function () {
        StandbyProcessing(2,button,"body");
    });
  });


  var ErrorModalTarget = ".error_message_area";
  var ErrorModalButton = ".error_confirmation_button";


  $(document).on("click", "#save-button", function (e) {

    e.preventDefault();
    var button = $(this);
    let f = $('#save-form');    
    StandbyProcessing(1,button,"body");  

    ErrorClear(ErrorModalButton,ErrorModalTarget);

    var errorsHtml = "";
    var LoginAgainFlg = false;
    var ErrorFlg = false;    
    
    var postal_code_front = $("#postal_code_front").val();
    var postal_code_back = $("#postal_code_back").val();

    var postal_code = (postal_code_front + postal_code_back).trim();    
    $('<input type="hidden" name="postal_code">').val(postal_code).appendTo(f);
    
    $.ajax({
        url: f.prop('action'), // 送信先
        type: f.prop('method'),
        dataType: 'json',
        data: f.serialize(),
    })
    .done(function (data, textStatus, jqXHR) {

      var result_array = data.result_array;

      if(result_array["result"] == 'success'){

        var url = result_array["url"];
        window.location.href = url;

      } else{
        
        errorsHtml += `<p>${result_array["message"]}</p>`;
        ErrorFlg = true;
        
      }    

    })
    .fail(function (data, textStatus, errorThrown) {

      ErrorFlg = true;      
        

        if (data.status == '422') {
            
            $.each(data.responseJSON.errors, function(key, value) {
                
              if (key === 'login_again') {
                LoginAgainFlg = true;
                // {{-- ループ抜け --}}
                return false;
              }

              if (key === 'postal_code') {

                AddInvalid("postal_code_front");
                AddInvalid("postal_code_back");                

              }else{

                AddInvalid(key);
              }              

              // {{-- errors を取得しメッセージを設定 --}}
              errorsHtml += `<button class="btn error_focus_button" data-target="${key}">${value[0]}</button><br>`; 

            });   


        } else {

            errorsHtml += '<p>Processing Error</p>';
            errorsHtml += `<p>${data.status}: ${errorThrown}</p>`;              
        }                   

    })
    // 通信終了後
    .always(function (data, textStatus, errorThrown) {

        StandbyProcessing(2,button);

        if(LoginAgainFlg){
          ShowLoginAgainModal();
          return false;
        }

        if(ErrorFlg){
          ShowErrorModal(errorsHtml , ErrorModalTarget,ErrorModalButton);
          return false;
        }
    });

  });


  $(document).on("click", ".delete-button", function (e) {

    e.preventDefault();
    var button = $(this);
    var employer_id = button.data('employer_id');
    var process = button.data('process');             

    var errorsHtml = "";
    var LoginAgainFlg = false;
    var ErrorFlg = false;  

    StandbyProcessing(1,button,"body");  

    ErrorClear(ErrorModalButton,ErrorModalTarget);


    var url = "{{ route('manager.master.m_employer.delete') }}";

    $.ajax({
        url: url, // 送信先
        type: "POST",
        dataType: 'json',
        data: {process:process , employer_id:employer_id},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    })
    .done(function (data, textStatus, jqXHR) {

      var result_array = data.result_array;

      if(result_array["result"] == 'success'){

        var url = result_array["url"];
        window.location.href = url;

      } else{
        
        errorsHtml += `<p>${result_array["message"]}</p>`;
        ErrorFlg = true;
        
      }    

    })
    .fail(function (data, textStatus, errorThrown) {

      ErrorFlg = true;      
      

      if (data.status == '422') {
          
          $.each(data.responseJSON.errors, function(key, value) {
              
            if (key === 'login_again') {
              LoginAgainFlg = true;
              // {{-- ループ抜け --}}
              return false;
            }             

          });   


      } else {

          errorsHtml += '<p>Processing Error</p>';
          errorsHtml += `<p>${data.status}: ${errorThrown}</p>`;              
      }        

    })
    // 通信終了後
    .always(function (data, textStatus, errorThrown) {

      StandbyProcessing(2,button);

      if(LoginAgainFlg){
        ShowLoginAgainModal();
        return false;
      }

      if(ErrorFlg){
        ShowErrorModal(errorsHtml , ErrorModalTarget,ErrorModalButton);
        return false;
      }

    });

    });



});
</script>
@endsection

