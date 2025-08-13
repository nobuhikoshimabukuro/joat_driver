@php
  $title = "資格・免許情報編集";
@endphp
@extends('Manager.Common.layouts_app')

@section('title', $title)


@section('pagestyle')
<!-- 画面別CSS -->
<style>  

</style>
@endsection

{{-- メインエリア --}}
@section('content')  


<div class="row align-items-center mb-3">
  <div class="col-auto">
    <h4 class="mb-0">{{$title}}</h4>
  </div>  
</div>




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

  

    <div id="" class="row m-0 p-1">    
      <div class="col-12 text-center">
        <button id="save-button" class="btn btn-outline-primary ms-1 me-1">登録</button>

        @if($m_license->license_id != 0)

          @if(!is_null($m_license->deleted_at))
            <button type="button" 
            data-process="2"
            data-license_id="{{$m_license->license_id}}"
            class="btn btn-outline-success delete-button ms-1 me-1">削除取消</button>       
          @else

            <button type="button" 
            data-process="1"
            data-license_id="{{$m_license->license_id}}"
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
    var license_id = button.data('license_id');
    var process = button.data('process');             

    var errorsHtml = "";
    var LoginAgainFlg = false;
    var ErrorFlg = false;  

    StandbyProcessing(1,button,"body");  

    ErrorClear(ErrorModalButton,ErrorModalTarget);


    var url = "{{ route('manager.master.m_license.delete') }}";

    $.ajax({
        url: url, // 送信先
        type: "POST",
        dataType: 'json',
        data: {process:process , license_id:license_id},
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

