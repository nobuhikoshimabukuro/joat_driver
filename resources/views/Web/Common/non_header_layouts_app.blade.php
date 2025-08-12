
<!doctype html>
<html lang="ja">
<head>

    @include('common/link')
    @yield('pagehead')
    <title>@yield('title')</title>
</head>

<style>


</style>

<div class="loader-area">
    <div class="loader">
    </div>
</div>

<body>

        

@yield('content')

<!--▽▽jQuery▽▽-->
<script>

    $(function(){
        setTimeout(function(){
            EndLoader();
        }, 1000);
    });
</script>
<!--△△jQuery△△-->

@yield('pagejs')


</body>

</html>