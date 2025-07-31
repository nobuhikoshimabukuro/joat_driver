@extends('driver.common.non_header_layouts_app')

@section('pagehead')
@section('title', 'driver login')  

@endsection
@section('content')

<style>

* { box-sizing:border-box; }

body {
  font-family: Helvetica;
  background: #eee;
  -webkit-font-smoothing: antialiased;
}

</style>

<div class="container">

    <div class="contents row justify-content-center p-0">

      <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-8 col-sm-10 col-11">

        <div class="card mt-5 p-3">
        
 
        <form action="{{ route('driver.login_check') }}" id='login_form' method="post" class="login-container" enctype="multipart/form-data">
          @csrf
          
            <label class="form-label">driver_cd</label>
            <input type="text" name="driver_cd"
            class="form-control {{ isset(session('errors')->driver_cd) ? 'is-invalid' : '' }}"
            value="{{ old('driver_cd') }}"
            maxlength="10"
            >          
          
          
            <label class="label">password</label>     
            <input type="password" name="password"
            class="form-control {{ isset(session('errors')->password) ? 'is-invalid' : '' }}"
            value="{{ old('password') }}"
            maxlength="10"
            >                        
          

          @if (session('errors'))
            <div class="alert alert-danger error_message_area">
                {{ session('errors')?->login_error_message }}
            </div>
          @endif  

          <div class="row m-0 mt-2 p-0">
            <div class="col-6 m-0 p-0">
            </div>
            <div class="col-6 m-0 p-0 text-end">
              <button type="button" id="login_button" class="btn btn-primary">ログイン</button>
            </div>
          
          </div>
        </form>

      </div>
    </div>
    </div>
</div>

@endsection

@section('pagejs')

<script type="text/javascript">


$('#login_button').click(function () {        
    login_process($(this)); 
});

function login_process($button){
    $('.is-invalid').removeClass('is-invalid');

    document.body.style.cursor = 'wait';

    $button.prop("disabled", true); 

    $('#login_form').submit(); 
}
    

</script>

@endsection

