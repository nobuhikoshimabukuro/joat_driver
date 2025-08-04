@extends('Manager.Common.layouts_app')

@section('title', '会社情報編集a')


@section('pagestyle')
<!-- 画面別CSS5 -->

<style>  

</style>
@endsection

{{-- メインエリア --}}
@section('content')  

<div class="row m-0 p-1 form-area">

  <div class="col-xxl-4 col-xl-4 col-lg-6 col-12 p-1 m-0">

    <label for="employer_name" class="form-label">求人元名</label>
    <input type="text" id="employer_name" 
    class="form-control" 
    value="{{$m_employer->employer_name}}"
    >

  </div>

  <div class="col-xxl-4 col-xl-4 col-lg-6 col-12 p-1 m-0">

    <label for="employer_name_kana" class="form-label">求人元名カナ</label>
    <input type="text" id="employer_name_kana" 
    class="form-control" 
    value="{{$m_employer->employer_name_kana}}"
    >

  </div>




</div>


@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">    
  </script>
@endsection
