<style>

header {
    background-color: #333;
    color: white;
    padding: 15px;
    text-align: center;
}  
p {
    font-size: 18px;
    line-height: 28px;
}

* {
    scroll-behavior: smooth;
}

#menuToggle {
    display: block;
    position: fixed;
    top: 25px;
    left: 10px;
    z-index: 99;
    -webkit-user-select: none;
    user-select: none;
}

#menuToggle a {
    text-decoration: none;
    color: #232323;
    transition: color 0.3s ease;
}

#menuToggle a:hover {
    color: tomato;
}

#menuToggle input {
    display: block;
    width: 40px;
    height: 32px;
    position: absolute;
    top: -7px;
    left: -5px;
    cursor: pointer;
    opacity: 0;
    z-index: 2;
    -webkit-touch-callout: none;
}

#menuToggle span {
    display: block;
    width: 33px;
    height: 4px;
    margin-bottom: 5px;
    position: relative;
    background: #cdcdcd;
    border-radius: 3px;
    z-index: 1;
    transform-origin: 4px 0px;
    transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0), background 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0), opacity 0.55s ease;
}

#menuToggle span:first-child {
    transform-origin: 0% 0%;
}

#menuToggle span:nth-last-child(2) {
    transform-origin: 0% 100%;
}

#menuToggle input:checked ~ span {
    opacity: 1;
    transform: rotate(45deg) translate(-2px, -1px);
    background: #232323;
}

#menuToggle input:checked ~ span:nth-last-child(3) {
    opacity: 0;
    transform: rotate(0deg) scale(0.2, 0.2);
}

#menuToggle input:checked ~ span:nth-last-child(2) {
    transform: rotate(-45deg) translate(0, -1px);
}

/* メニューのスタイル */
#menu {
    position: absolute;
    max-width: 400px;
    width: 100vw;
    max-height: 100vh;
    margin: -100px 0 0 -50px;
    padding: 50px;
    padding-top: 125px;
    box-sizing: border-box;
    overflow-y: auto;
    background: #ededed;
    list-style-type: none;
    -webkit-font-smoothing: antialiased;
    transform-origin: 0% 0%;
    transform: translate(-100%, 0);
    transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0);
    box-shadow: 4px 0 6px rgba(0, 0, 0, 0.1);
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

#menu li {
    padding: 10px 0;
    font-size: 22px;
}

#menu li label {
    cursor: pointer;
}

#menuToggle input:checked ~ ul {
    transform: none;
}



/* メディアクエリ: スマートフォンの場合（幅768px以下） */
@media (max-width: 768px) {
    #menu {
    width: 100vw; /* スマートフォンではメニューが全画面 */
    margin: -100px 0 0 -10px;
    }
}

/* メディアクエリ: PCの場合（幅769px以上） */
@media (min-width: 769px) {
    #menu {
    width: 35vw; /* PCではメニューが左部で35%の幅 */
    }
}
  </style>
  

  <body>
    <header class="pb-1">
        <h2>AppName</h2>    
        <h4>{{session('driver_last_name')}} {{session('driver_first_name')}}</h4>    

    </header>
  
    <nav role="navigation">
      <div id="menuToggle">
        <input type="checkbox" id="menuCheckbox" />
        <span></span>
        <span></span>
        <span></span>
  
        <ul id="menu">            
            {{-- @foreach (session("driver_menu_info") as $info)
                @if($info->header_flg == 1)
                    <li>
                        <label>
                            <a href="{{$info->href}}">{{$info->title}}</a>                    
                        </label>         
                        
                        @if(route('driver.pickup_request_check') == $info->href && session('pickup_request_count') > 0)
                        <i class="fas fa-exclamation-circle item-flash order_alert"></i>                        
                        @endif

                    </li>
                @endif            
            @endforeach  --}}
        </ul>
      </div>
    </nav>
  </body>
  