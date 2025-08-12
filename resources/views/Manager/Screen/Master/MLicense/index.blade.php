@extends('Manager.Common.layouts_app')

@section('title', '資格・免許情報一覧')


@section('pagestyle')
<!-- 画面別CSS4 -->
<style>  
</style>
@endsection

{{-- メインエリア --}}
@section('content')  


<table class="table table-hover">
  <tr>
    <th class="w-150px">
      <button class="btn btn-outline-primary page-transition-button mb-2"                                
      data-url="{{route('manager.master.m_license.entry')}}"
      data-process="1"
      >新規登録</button>
    </th>

    <th class="w-150px">
      資格・免許ID
    </th>

    <th>
      資格・免許名
    </th>    

    <th>
      備考
    </th>    

  </tr>

  @foreach ($m_license as $item)

  <tr data-target_row="{{$item->license_id }}">
    <td>
      <button class="btn btn-outline-primary page-transition-button mb-2"                                
      data-url="{{route('manager.master.m_license.entry')}}?license_id={{$item->license_id }}"
      data-process="1"
      >編集</button>
    </td>

    <td>
      {{ $item->license_id }} 
    </td>

    <td>
      <ruby>
        {{ $item->license_name }}
        <rt>{{ $item->license_name_kana }}</rt>
      </ruby>
    </td>

    <td>{!! nl2br(e($item->remarks)) !!}</td>
    
  </tr>
  @endforeach

</table>


@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">
  
   
      
  </script>
@endsection
