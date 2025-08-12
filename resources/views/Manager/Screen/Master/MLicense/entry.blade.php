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

  .corporate_number_area
   {
    display: none; /* 初期状態で非表示にする */
  }

</style>
@endsection

{{-- メインエリア --}}
@section('content')  

<button id="save-button" class="btn btn-outline-primary">登録</button>
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
        class="form-control w-100px" 
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


@endsection
@section('pagejs')  
<script type="text/javascript">
$(document).ready(function () {

 

  $(document).on("click", "#save-button", function (e) {

    e.preventDefault();
    var button = $(this);
    let f = $('#save-form');    
    StandbyProcessing(1,button,"body");
    has_error_flg = false;

    
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

           
        }    

    })
    .fail(function (data, textStatus, errorThrown) {

        has_error_flg = true;

        errorsHtml = '<div class="alert alert-danger text-left">';

        if (data.status == '422') {

            var errorsHtml = "";
            $.each(data.responseJSON.errors, function(key, value) {
                

              if (key === 'postal_code') {
                $(`[name="postal_code_front"]`).addClass('is-invalid');
                $(`[name="postal_code_back"]`).addClass('is-invalid');
              }else{
                $(`[name="${key}"]`).addClass('is-invalid');
              }              

               // {{-- errors を取得しメッセージを設定 --}}
               errorsHtml += `<li>${value[0]}</li>`;

                
            });


        } else {

            errorsHtml += '<li>Processing Error</li>';
            errorsHtml += `<li>${data.status}: ${errorThrown}</li>`;              
        }
        
        errorsHtml += '</div>';              

    })
    // 通信終了後
    .always(function (data, textStatus, errorThrown) {

        StandbyProcessing(2,button);

        if(has_error_flg){

            if(data.responseJSON && data.responseJSON.errors && data.responseJSON.errors.hasOwnProperty('login_again')){                

            }else{               
               
            }

        }
    });

  });



});
</script>
@endsection

