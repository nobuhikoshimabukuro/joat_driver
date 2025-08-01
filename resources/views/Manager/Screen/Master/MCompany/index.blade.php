@extends('Manager.Common.layouts_app')

@section('title', '会社マスタ')


@section('pagestyle')
<!-- 画面別CSS -->
<style>  
</style>
@endsection

{{-- メインエリア --}}
@section('content')  


<table class="table table-hover">
  <tr>
    <th>
    </th>

    <th>
    </th>

    <th>
    </th>
  </tr>

  @foreach ($prefecture_info as $pref)
  <tr>
    <th>
      {{ $pref->prefecture_code }} 
    </th>

    <th>
      {{ $pref->prefecture }}
    </th>

    <th>
      {{ $pref->prefecture_kana }}
    </th>
  </tr>
  @endforeach

</table>


@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">    
  </script>
@endsection
