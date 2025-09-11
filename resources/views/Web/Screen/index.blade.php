@php
  $title = "トップ";
@endphp

@extends('Web.Common.layouts_app')

@section('title',$title)


@section('pagestyle')
<!-- 画面別CSS -->
<style>  


/* 参考サイト
https://junpei-sugiyama.com/swiper-summary/
*/

.swiper-slide {
  overflow: hidden;
  position: relative;
}

.swiper-img{
  height: 100%;
}
.swiper-slide img {
  height: 100%;
  width: 100%;
}

.swiper-text {
  color: #fff;  
  text-shadow: 1px 1px 2px #333;  
  width: 70%;
  position: absolute;
  width: 70%;
}

.swiper-text-position1 {
  top: 5%;
  left: 5%;  
}

.swiper-text-position2 {
  top: 5%;
  right: 5%;  
}

.swiper-text-position3 {
  bottom: 5%;
  left: 5%;  
}

.swiper-text-position4 {
  bottom: 5%;
  right: 5%;  
}

.swiper-title {
  font-size: clamp(16px, 3vw, 50px);
  font-weight: 700;
}
.swiper-desc {
  font-size: clamp(12px, 2vw, 30px);
  line-height: 1.5;
  margin-top: 3%;
}


.swiper-button{
  color: #fff;  
  text-shadow: 1px 1px 1px #333;  
  font-weight: 400;
}

/* スライドの動き等速 */
.swiper-wrapper {
  transition-timing-function: linear;
}


</style>
@endsection

{{-- メインエリア --}}
@section('content')  


<div class="swiper swiper1">

  <div class="swiper-wrapper">

    <div class="swiper-slide">

      <div class="swiper-img" data-swiper-parallax-x="90%">
        <img class="image" src="{{ asset('image/top/001.jpg') }}">    
      </div>

      <div class="swiper-text swiper-text-position1">
        <h3 class="swiper-title">テキスト1</h3>
        <p class="swiper-desc" data-swiper-parallax-x="70%">          
        </p>
      </div>

    </div>


    <div class="swiper-slide">
      
      <div class="swiper-img" data-swiper-parallax-x="90%">
        <img class="image" src="{{ asset('image/top/002.JPG') }}">    
      </div>

      <div class="swiper-text swiper-text-position3">
        <h3 class="swiper-title">テキスト2</h3>
        <p class="swiper-desc" data-swiper-parallax-x="70%">          
        </p>
      </div>

    </div>
   

    <div class="swiper-slide">
      
      <div class="swiper-img" data-swiper-parallax-x="90%">
        <img class="image" src="{{ asset('image/top/003.JPG') }}">    
      </div>

      <div class="swiper-text swiper-text-position1">
        <h3 class="swiper-title">テキスト3</h3>
        <p class="swiper-desc" data-swiper-parallax-x="70%">          
        </p>
      </div>

    </div>

    <div class="swiper-slide">
      
      <div class="swiper-img" data-swiper-parallax-x="90%">
        <img class="image" src="{{ asset('image/top/004.JPG') }}">    
      </div>
      
      <div class="swiper-text swiper-text-position3">
        <h3 class="swiper-title">テキスト4</h3>
        <p class="swiper-desc" data-swiper-parallax-x="70%">          
        </p>
      </div>

    </div>

  </div>
  <!-- ページネーション -->
  <div class="swiper-pagination"></div>
  <!-- 前後の矢印 -->
  <div class="swiper-button swiper-button-prev"></div>
  <div class="swiper-button swiper-button-next"></div>
</div>



@endsection


@section('pagejs')  
  <!-- 画面別script -->
  <script type="text/javascript">

  

document.addEventListener('DOMContentLoaded', function() {

const swiper1 = new Swiper(".swiper1", {
    loop: true, // ループ
    speed: 900, // 少しゆっくり(デフォルトは300)
    slidesPerView: 1, // 一度に表示する枚数
    spaceBetween: 0, // スライド間の距離
    centeredSlides: true, // アクティブなスライドを中央にする
    autoplay: {
        delay: 8000, // 8秒後に次のスライド                
        disableOnInteraction: false, // 矢印をクリックしても自動再生を止めない
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints:{       
        768: {
            slidesPerView: 1.2,
            spaceBetween: 15,
            speed: 1100,
        },
        1024: {
            slidesPerView: 1.3,
            spaceBetween: 20,
            speed: 1200,
        },
        1200: {
            slidesPerView: 1.5,
            spaceBetween: 30,
            speed: 1300,
        }
    },
    on: {
        init: adjustSwiperHeight,
        resize: adjustSwiperHeight,
        slideChange: adjustSwiperHeight // スライドが変更されたときに高さを調整
    }
});

function adjustSwiperHeight() {
    var swiperSlide = document.querySelector('.swiper-slide');
    if (swiperSlide) {
        var slideWidth = swiperSlide.offsetWidth;
        var swiperContainer = document.querySelector('.swiper');
        var goldenRatio = 1.618;
        swiperContainer.style.height = (slideWidth / goldenRatio) + 'px';
    }
}

// 初期ロード時に高さを調整
adjustSwiperHeight();

// ウィンドウのリサイズ時に高さを調整
window.addEventListener('resize', adjustSwiperHeight);

const swiper2 = new Swiper(".swiper2", {
  loop: true, // ループ
  slidesPerView: 1.1, // 一度に表示する枚数
  speed: 8000, // ループの時間
  allowTouchMove: true, // スワイプ有効
  autoplay: { //自動再生
    delay: 0, // 途切れなくループ
    disableOnInteraction: false, // 矢印をクリックしても自動再生を止めない
  },
  breakpoints:{       
        768: {
            slidesPerView: 2,                    
        },
        1024: {
            slidesPerView: 2.5,
        },
        1200: {
            slidesPerView: 3,                    
        }
    }
});

});





   
      
  </script>
@endsection
