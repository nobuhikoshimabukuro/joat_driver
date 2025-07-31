{{-- 再ログインモーダル--}}
<div class="modal fade" id="login_again-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="login_again-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id=""></h5>               
            </div>

            <div class="modal-body">
                <a href="{{ route('user.login') }}">一定の時間が経過した為、ログイン者の情報が取得できません。
                <br>
                <span class="text-decoration-underline">再ログイン</span>をお願い致します。   
                </a>

           
            </div>

            <div class="modal-footer text-end">            
                
                <a href="{{ route('user.login') }}" class="btn btn-primary">
                    再ログインはこちら
                </a>
                

            </div>

        </div>
    </div>
</div>