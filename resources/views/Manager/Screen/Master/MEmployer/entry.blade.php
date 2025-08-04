@extends('Manager.Common.layouts_app')

@section('title', '会社情報編集')


@section('pagestyle')
<!-- 画面別CSS3 -->
<style>  

  .form-area label{
    font-weight: bold;
  }

  .corporate_number_area
   {
    display: none; /* 初期状態で非表示にする */
  }

</style>
@endsection

{{-- メインエリア --}}
@section('content')  

<div class="form-area">

  <div class="row m-0 p-1">

    <div class="col-xxl-2 col-xl-3 col-lg-3 col-12 p-1 m-0">

      <label for="employer_category" class="form-label">求人元区分</label>
      <select id="employer_category" class='form-control w-auto'>    
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

    <div class="col-xxl-3 col-xl-3 col-lg-6 col-12 p-1 m-0 corporate_number_area ">
      <label for="corporate_number" class="form-label">法人番号</label>
      <input type="text" id="corporate_number" 
      class="form-control text-end w-180px" 
      value="{{$m_employer->corporate_number}}"
      maxlength="13"
      >

    </div>

  </div>
  


  


  <div class="row m-0 p-1">

    <div class="col-12 p-1 m-0">
      <label for="login_info_change_flg">ログイン情報を変更する</label>
      <input type="checkbox" id="login_info_change_flg" class="ms-2 checkbox-size-m" value="1">      
    </div>  
  </div>  

  <div class="row m-0 p-1 d-none">    
      <div class="col-xxl-4 col-xl-4 col-lg-6 col-12 p-1 m-0">

        <label for="employer_cd" class="form-label">ログインCD</label>
        <input type="text" id="employer_cd" 
        class="form-control" 
        value="{{$m_employer->employer_cd}}"
        >

      </div>

      <div class="col-xxl-4 col-xl-4 col-lg-6 col-12 p-1 m-0">

        <label for="password" class="form-label">password</label>
        <input type="text" id="password" 
        class="form-control" 
        value=""
        >     
    </div>   


  </div>


</div>


@endsection

@section('pagejs')  
<script type="text/javascript">    
  document.addEventListener("DOMContentLoaded", function () {
    const employerCategory = document.getElementById("employer_category");
    const corporateNumberArea = document.querySelector(".corporate_number_area");
    const loginInfoCheckbox = document.getElementById("login_info_change_flg");
    const loginInfoArea = document.querySelector(".login_info_area");

    function toggleCorporateNumberArea() {
      if (employerCategory.value === "2") {
        corporateNumberArea.style.display = "block";
      } else {
        corporateNumberArea.style.display = "none";
      }
    }

    function toggleLoginInfoArea() {
      if (loginInfoCheckbox.checked) {
        loginInfoArea.style.display = "block";
      } else {
        loginInfoArea.style.display = "none";
      }
    }

    // 初期表示
    toggleCorporateNumberArea();
    toggleLoginInfoArea();

    // イベント設定
    employerCategory.addEventListener("change", toggleCorporateNumberArea);
    loginInfoCheckbox.addEventListener("change", toggleLoginInfoArea);
  });
</script>
@endsection

