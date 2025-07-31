@extends('driver.common.non_header_layouts_app')

@section('pagehead')
@section('title', 'driver login')  

@endsection
@section('content')

<style>
body {
  /* background: #456; */
  background: rgb(219, 219, 219);
  font-family: 'Open Sans', sans-serif;
}

.login {
  width: 500px;
  margin: 16px auto;
  font-size: 16px;
}

/* Reset top and bottom margins from certain elements */
.login-header,
.login p {
  margin-top: 0;
  margin-bottom: 0;
}

/* The triangle form is achieved by a CSS hack */
.login-triangle {
  width: 0;
  margin-right: auto;
  margin-left: auto;
  border: 12px solid transparent;
  border-bottom-color: #28d;
}

.login-header {
  background: #28d;
  padding: 20px;
  font-size: 1.4em;
  font-weight: normal;
  text-align: center;
  text-transform: uppercase;
  color: #fff;
}

.login-container {
  background: #ebebeb;
  padding: 12px;
}

/* Every row inside .login-container is defined with p tags */
.login p {
  padding: 12px;
}

.login input {
  box-sizing: border-box;
  display: block;
  width: 100%;
  border-width: 1px;
  border-style: solid;
  padding: 8px;
  outline: 0;
  font-family: inherit;
  font-size: 26px;
}

.login input[type="driver_cd"],
.login input[type="password"] {
  /* background: #fff;
  border-color: #bbb; */
  color: #555;
}

/* Text fields' focus effect */
.login input[type="driver_cd"]:focus,
.login input[type="password"]:focus {
  border-color: #888;
}

.login input[type="button"] {
  background: #28d;
  border-color: transparent;
  color: #fff;
  cursor: pointer;
}

.login input[type="button"]:hover {
  background: #17c;
}

/* Buttons' focus effect */
.login input[type="button"]:focus {
  border-color: rgb(4, 32, 60);
  background: rgb(7, 75, 131);  
}

.error_message_area{
  margin-left: 10px;
  margin-right: 10px;
}

</style>

<div class="container">
    <div class="contents row justify-content-center p-0">

        <div class="login">
            <div class="login-triangle"></div>
            
            <h2 class="login-header">AppName</h2>
          
            <form action="{{ route('driver.login_check') }}" id='login_form' method="post" class="login-container" enctype="multipart/form-data">
            @csrf
              
              <p><input type="driver_cd" id="driver_cd" name="driver_cd" placeholder="driver_cd" 
                value="{{ old('driver_cd') }}"
                class="form-control  {{ isset(session('errors')->driver_cd) ? 'is-invalid' : '' }}"
                maxlength="10"
                >
              </p>
              <p><input type="password" id="password" name="password" placeholder="password" autocomplete="off"
                class="form-control  {{ isset(session('errors')->password) ? 'is-invalid' : '' }}"
                maxlength="15"
                >
              </p>

              <p><input type="button" id="login_button" value="ログイン"></p>

              @if (session('errors'))
                <div class="alert alert-danger error_message_area">
                    {{ session('errors')?->login_error_message }}
                </div>
              @endif  
            </form>

         

        </div>

    </div>
</div>

@endsection

@section('pagejs')

<script type="text/javascript">



    
  $(function(){

    $(document).ready(function () {        
       $('#driver_cd').focus();
   });

    
  $("#login_form").keydown(function(e) {

    if(e.which == 13) {            
        // 判定
        if( document.getElementById("login_button") == document.activeElement ){
            
            login_process();

        }else if( document.getElementById("driver_cd") == document.activeElement ){

            $('#password').focus();
            return false;

        }else if( document.getElementById("password") == document.activeElement ){

            $('#login_button').focus();
            return false;

        }else{
            return false;
        }            
    }

  });    

  $('#login_button').click(function () {        
    login_process();
  });


  function login_process(){

        
    $('.is-invalid').removeClass('is-invalid');

    
    //{{-- マウスカーソルを待機中に --}}         
    document.body.style.cursor = 'wait';

    // ２重送信防止
    // 保存tを押したらdisabled, 10秒後にenable
    $(this).prop("disabled", true);

    // 確認
    $('#login_form').submit(); 

    }


  });



    

</script>

@endsection

