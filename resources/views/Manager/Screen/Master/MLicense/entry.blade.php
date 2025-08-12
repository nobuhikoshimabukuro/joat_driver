@extends('Manager.Common.layouts_app')

@section('title', '資格・免許情報編集')


@section('pagestyle')
<!-- 画面別CSS3 -->
<style>  

  .form-area label{
    font-weight: bold;
    margin-top: 0.3rem;
    margin-bottom: 0.9rem;
    
  }

</style>
@endsection

{{-- メインエリア --}}
@section('content')  

<button id="save-button" class="btn btn-outline-primary">登録</button>
<button type="button" class="btn btn-outline-danger error_confirmation_button d-none">エラー確認</button>
<form id='save-form' class="form-area" action="{{ route('manager.master.m_license.save') }}" method="post" enctype="multipart/form-data">
  @csrf

  
    <input type="hidden" id="license_id" name="license_id" value="{{$m_license->license_id}}">

    <div id="" class="row m-0 p-1">    
      <div class="col-xxl-4 col-xl-4 col-lg-5 col-12 p-1 m-0">

        <label for="license_name" class="form-label">資格・免許名</label>
        <input type="text" id="license_name" name="license_name"
        class="form-control" 
        value="{{$m_license->license_name}}"
        >

      </div>

      <div class="col-xxl-4 col-xl-4 col-lg-5 col-12 p-1 m-0">

          <label for="license_name_kana" class="form-label">資格・免許名カナ</label>
          <input type="text" id="license_name_kana" name="license_name_kana"
          class="form-control" 
          value="{{$m_license->license_name_kana}}"        
          >     
      </div>   

      <div class="col-xxl-4 col-xl-4 col-lg-2 col-12 p-1 m-0">

        <label for="display_order" class="form-label">表示順</label>
        <input type="text" id="display_order" name="display_order"
        class="form-control w-70px text-end" 
        value="{{$m_license->display_order}}"        
        maxlength="3"
        >     
      </div>   


    </div>

  

    <div id="" class="row m-0 p-1">    
      <div class="col-xxl-6 col-xl-6 col-lg-6 col-12 p-1 m-0">
        <label for="remarks" class="form-label">備考</label>
        <textarea id="remarks" name="remarks" class="form-control" rows="4">{{$m_license->remarks}}</textarea>
      </div>   
    </div>

  


  </div>


</form>


@include('Manager.Common.error_modal')
@include('Manager.Common.login_again_modal')

@endsection
@section('pagejs')  
<script type="text/javascript">
$(document).ready(function () {

 

  var ErrorModalTarget = ".error_message_area";

  $(document).on("click", "#save-button", function (e) {

    e.preventDefault();
    var button = $(this);
    let f = $('#save-form');    
    StandbyProcessing(1,button,"body");  

    ErrorClear(".error_confirmation_button",ErrorModalTarget);

    var errorsHtml = "";
    var LoginAgainFlg = false;
    var ErrorFlg = false;    
    
    
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
              errorsHtml += `<button class="btn error_focus_button" data-target="${key}">${value[0]}</button>`; 

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
          ShowErrorModal(errorsHtml , ErrorModalTarget);
          return false;
        }

    });

  });



});
</script>
@endsection

