    
@php 
$system_version = "?system_version=" . env('system_version');
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
<link href="{{ asset('css/bootstrap53/bootstrap.css') . $system_version }}" rel="stylesheet">    
<link href="{{ asset('css/style.css') . $system_version }}" rel="stylesheet">
<link href="{{ asset('css/width.css') . $system_version }}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

  
<script src="{{ asset('js/app.js') . $system_version}}"></script>
<script src="{{ asset('js/bootstrap53/bootstrap.js') . $system_version}}"></script>
<script src="{{ asset('js/common.js') . $system_version}}"></script>
<script src="{{ asset('js/common_ajax.js') . $system_version}}"></script>
<script src="{{ asset('js/jquery-3.6.0.min.js') . $system_version}}"></script>
<script src="{{ asset('js/fontawesome.js') . $system_version}}"></script>


<script>
const Routes = {      
};
</script>
