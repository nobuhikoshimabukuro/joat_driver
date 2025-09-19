@extends('Manager.Common.layouts_app')

@section('title', 'test')


@section('pagestyle')
<!-- 画面別CSS -->
<style>  
</style>
@endsection

{{-- メインエリア --}}
@section('content')  




<form id="save-form" class="form-area" action="{{ route('manager.excel_upload') }}" method="post" enctype="multipart/form-data">
  @csrf


  
  <div class="row m-0 p-1">
      <div class="col-xxl-6 col-xl-6 col-lg-6 col-12 p-1 m-0">
          <label for="excel" class="form-label">Excel</label>
          <input type="file" id="excel" name="excel" class="form-control" accept=".xls,.xlsx,.xlsm" required>
      </div>
      
  </div>

  <div class="row m-0 p-1">
      <div class="col-12 text-center">
          <button id="upload-button" class="btn btn-outline-primary ms-1 me-1">アップロード</button>          
      </div>
  </div>
</form>





<div class="row m-0 p-1">

  <div class="col-xxl-2 col-xl-3 col-lg-6 col-12 p-1 m-0">
    
    <label for="job_title" class="form-label">求人名</label>
    <input type="text" id="job_title" name="job_title"
    class="form-control" 
    value=""
    >

  </div>

  <div class="col-xxl-2 col-xl-3 col-lg-6 col-12 p-1 m-0">

    <label for="job_type" class="form-label">求人タイプ</label>
    <input type="text" id="job_type" name="job_type"
    class="form-control" 
    value=""
    >

  </div>

  
  

</div>



@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">    

    

    $(document).on("click", "#upload-button", function (e) {

      e.preventDefault();
      var button = $(this);
      let f = $('#save-form');
      
    
      StandbyProcessing(1,button,"body");  

    

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

          var return_array = result_array["return_array"];

          // フロントの入力IDとreturn_arrayのキーをマッピング
          var fields = ['job_title', 'job_type'];

          fields.forEach(function(key) {
          // 画面に要素が存在するか確認
              if ($('#' + key).length && return_array[key] !== undefined) {
                  $('#' + key).val(return_array[key]);
              }
          });

        } else{
          
          
           
        }    

    })
    .fail(function (data, textStatus, errorThrown) {

       

    })
    // 通信終了後
    .always(function (data, textStatus, errorThrown) {

      StandbyProcessing(2,button);

    });

  });

  </script>
@endsection
