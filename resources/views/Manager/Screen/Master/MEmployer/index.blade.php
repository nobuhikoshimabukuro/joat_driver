@extends('Manager.Common.layouts_app')

@section('title', '求人元情報一覧')


@section('pagestyle')
<!-- 画面別CSS4 -->
<style>  
</style>
@endsection

{{-- メインエリア --}}
@section('content')  


<table class="table table-hover">
  <tr>
    <th>
      求人元ID      
    </th>

    <th>
      求人元名
    </th>
    

    <th>

      <button class="btn btn-outline-primary page-transition-button mb-2"                                
      data-url="{{route('manager.master.m_employer.entry')}}"
      data-process="1"
      >新規登録</button>
      
    </th>
  </tr>

  @foreach ($m_employer as $item)
  <tr>
    <td>
      {{ $item->employer_id }} 
    </td>

    <td>
      <ruby>
        {{ $item->employer_name }}
        <rt>{{ $item->employer_name_kana }}</rt>
      </ruby>
    </td>

    <td>
      
    </td>
  </tr>
  @endforeach

</table>


@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">    
  </script>
@endsection
