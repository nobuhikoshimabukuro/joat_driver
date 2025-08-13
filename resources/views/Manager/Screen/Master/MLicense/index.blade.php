@extends('Manager.Common.layouts_app')

@section('title', '資格・免許情報一覧')


@section('pagestyle')
<!-- 画面別CSS -->
<style>  

.table-responsive {
  max-height: 80vh; /* 必要に応じて高さ指定 */
  overflow-y: auto;  /* 縦スクロール用 */
  overflow-x: auto;  /* 横スクロール用 */
}

.table-fixed-header thead th {
  position: sticky;
  top: 0;
  background: #fff;
  z-index: 5; /* Bootstrapのbtnより上に */
}
.table-nowrap th,
.table-nowrap td {
  white-space: nowrap;
}

.delete_row td {
  background-color: #f8f6e9 !important;
  color: #fb4946 !important;
}

@keyframes blink {
  0%, 100% { border-color: transparent; }
  50% { border-color: red; }
}

.highlight-flash {
  animation: blink 1s ease-in-out infinite; /* 無限ループ */
  border: 2px solid red;
  border-radius: 4px;
  padding: 2px;
  background-color: #fff8f8;
}


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
    <h4 class="mb-0">資格・免許情報一覧</h4>
  </div>
  <div class="col-auto">
    <button class="btn btn-outline-primary page-transition-button"
            data-url="{{route('manager.master.m_license.entry')}}"
            data-process="1">新規登録</button>

    <button type="button" class="btn btn-outline-danger error_confirmation_button d-none">エラー確認</button>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-hover table-fixed-header table-nowrap mb-0">
    <thead>
      <tr>
        <th class="w-100px {{$TextClass[$TextClassIndex++]}}"></th>
        <th class="w-150px {{$TextClass[$TextClassIndex++]}}">資格・免許ID</th>
        <th class="{{$TextClass[$TextClassIndex++]}}">資格・免許名</th>
        <th class="{{$TextClass[$TextClassIndex++]}}">備考</th>
        <th class="w-100px {{$TextClass[$TextClassIndex++]}}"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($m_license as $item)
        @php 
          $TextClassIndex = 0; 
          $DeleteFlg = false;
          if(!is_null($item->deleted_at)){
            $DeleteFlg = true;
          }
        @endphp
        <tr data-target_row="{{$item->license_id}}" class="@if($DeleteFlg) delete_row  @endif">
          <td class="{{$TextClass[$TextClassIndex++]}}">
            <button class="btn btn-outline-primary page-transition-button mb-2"
              data-url="{{route('manager.master.m_license.entry')}}?license_id={{$item->license_id }}"
              data-process="1">編集</button>
          </td>
          <td class="{{$TextClass[$TextClassIndex++]}}">{{ $item->license_id }}</td>
          <td class="{{$TextClass[$TextClassIndex++]}}">
            <ruby>
              {{ $item->license_name }}
              <rt>{{ $item->license_name_kana }}</rt>
            </ruby>
          </td>
          <td class="{{$TextClass[$TextClassIndex++]}}">{!! nl2br(e($item->remarks)) !!}</td>

          <td class="{{$TextClass[$TextClassIndex++]}}">
            @if($DeleteFlg)
              <button type="button" 
              data-process="2"
              data-license_id="{{$item->license_id}}"
              class="btn btn-outline-success delete-button">削除取消</button>              
            @else
              <button type="button" 
              data-process="1"
              data-license_id="{{$item->license_id}}"
              class="btn btn-outline-danger delete-button">削除</button>             
            @endif
          </td>

        </tr>
      @endforeach
    </tbody>
  </table>
</div>


@include('Manager.Common.info_modal')
@include('Manager.Common.error_modal')
@include('Manager.Common.login_again_modal')

@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">

 
    var completion_info = @json($completion_info ?? null);
    if(completion_info){
      DataUpdateSelectRow(completion_info);
    }

    
 

    var ErrorModalTarget = ".error_message_area";
    var ErrorModalButton = ".error_confirmation_button";

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
  
   
      
  </script>
@endsection
