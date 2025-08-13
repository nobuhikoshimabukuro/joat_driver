@php
  $title = "住所情報";
@endphp

@extends('Manager.Common.layouts_app')

@section('title',$title)


@section('pagestyle')
<!-- 画面別CSS -->
<style>  



</style>
@endsection

{{-- メインエリア --}}
@section('content')  

@php

  use App\Original\CommonArray;
  $textAlignClasses = CommonArray::GetTextAlignClasses();

  $TextClass=[];
  $TextClass[]= $textAlignClasses[1];
  $TextClass[]= $textAlignClasses[1];
  $TextClass[]= $textAlignClasses[0];
  $TextClass[]= $textAlignClasses[0];
  $TextClass[]= $textAlignClasses[0];
  $TextClassIndex = 0;
@endphp

<div class="row align-items-center mb-3">
  <div class="col-auto">
    <h4 class="mb-0">{{$title}}</h4>
  </div>
  <div class="col-auto">
   

    
  </div>
</div>

<div class="card mt-3 p-3">
  {{-- <h2>住所情報更新手順</h2> --}}
  {{-- <ul>
      <li>都道府県情報は必ず<span>全国一括</span>をダウンロードしてください。 <button class="btn btn-outline-primary page-transition-button"                                
          data-url="https://www.post.japanpost.jp/zipcode/dl/kogaki-zip.html"
          data-process="2"
          >郵政提供ページ（都道府県）                      
          </button></li>
    
      <li>事業所情報は必ず<span>最新全データ</span>ダウンロードしてください。 <button class="btn btn-outline-primary page-transition-button"                                
          data-url="https://www.post.japanpost.jp/zipcode/dl/jigyosyo/index-zip.html"
          data-process="2"
          >郵政提供ページ（事業所情報）                      
          </button></li>

      <li>住所情報は郵政提供ページからzipファイルをダウンロードします</li>
      <li>解凍後このボタンを<button type="button" class="btn btn-primary save-modal-open" data-user_id="0" >CSV UP</button>
          クリックし.csvをセットしアップロードしてください。</li>

  </ul> --}}

  
  <div class="card-header fw-bold">
      <h5 class="mb-0">住所情報更新手順</h5>
  </div>

  <ul class="list-group mb-4">
      <li class="list-group-item d-flex justify-content-between align-items-start">
          <div>
          <strong>都道府県情報：</strong>
          <span class="text-primary fw-bold">全国一括</span> を必ずダウンロードしてください。
          </div>
          <button class="btn btn-primary page-transition-button btn-sm "                                
          data-url="https://www.post.japanpost.jp/zipcode/dl/kogaki-zip.html"
          data-process="2"
          >郵政提供ページ（都道府県）                      
          </button></li>
      </li>
      
      <li class="list-group-item d-flex justify-content-between align-items-start">
          <div>
          <strong>事業所情報：</strong>
          <span class="text-success fw-bold">最新全データ</span> を必ずダウンロードしてください。
          </div>
          <button class="btn btn-success page-transition-button btn-sm "                                
          data-url="https://www.post.japanpost.jp/zipcode/dl/jigyosyo/index-zip.html"
          data-process="2"
          >郵政提供ページ（事業所情報）                      
          </button>
      </li>
      
      <li class="list-group-item">
          <strong>住所情報：</strong> 郵政提供ページからファイルをダウンロードしてください。
      </li>
      
      <li class="list-group-item d-flex justify-content-between align-items-center">
          <div>
              <code>.zip</code> ファイルを解凍すると <code>.csv</code> ファイルが生成されます。<br>
              都道府県CSVと事業所個別郵便番号CSVにセットしアップロードしてください。
          </div>                        
      </li>
  </ul>
</div>     


<form id="save-form" class="form-area" action="{{ route('manager.master.m_address.save') }}" method="post" enctype="multipart/form-data">
  @csrf

  <div class="row m-0 p-1">
      <div class="col-xxl-6 col-xl-6 col-lg-6 col-12 p-1 m-0">
          <label for="normal_csv" class="form-label">都道府県CSV</label>
          <input type="file" id="normal_csv" name="normal_csv" class="form-control" accept=".csv,.CSV" required>
      </div>

      <div class="col-xxl-6 col-xl-6 col-lg-6 col-12 p-1 m-0">
          <label for="jigyosyo_csv" class="form-label">事業所個別郵便番号CSV</label>
          <input type="file" id="jigyosyo_csv" name="jigyosyo_csv" class="form-control" accept=".csv,.CSV" required>
      </div>
  </div>

  <div class="row m-0 p-1">
      <div class="col-12 text-center">
          <button id="upload-button" class="btn btn-outline-primary ms-1 me-1">アップロード</button>
          <button type="button" class="btn btn-outline-danger error_confirmation_button d-none">エラー確認</button>
      </div>
  </div>
</form>




@include('Manager.Common.info_modal')
@include('Manager.Common.error_modal')
@include('Manager.Common.login_again_modal')

@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">    

    var ErrorModalTarget = ".error_message_area";
    var ErrorModalButton = ".error_confirmation_button";

    $(document).on("click", "#upload-button", function (e) {

      e.preventDefault();
      var button = $(this);
      let f = $('#save-form');
      
      var errorsHtml = "";
      var LoginAgainFlg = false;
      var ErrorFlg = false;  

      StandbyProcessing(1,button,"body");  

      ErrorClear(ErrorModalButton,ErrorModalTarget);

      $.ajax({
        url: f.prop('action'), // 送信先
        type: f.prop('method'),
        dataType: 'json',
        data: new FormData(f[0]), // ファイルデータを送信
        contentType: false,
        processData: false,
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
              
              AddInvalid(key);

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
  
   
      
  </script>
@endsection
