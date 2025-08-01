<?php

use Diglactic\Breadcrumbs\Breadcrumbs;


// パンくず設定一覧（親ルート名,ルート名,タイトル,URL）
$breadcrumbs = [

    // 管理会社start
    // ダッシュボード
    [
        'parent' => null,
        'name' => 'manager.dashboard',
        'label' => 'DashBoard',
        'url' => route('manager.dashboard'),
    ],

    // 利用会社start
    // ダッシュボード
    [
        'parent' => null,
        'name' => 'company.dashboard',
        'label' => 'DashBoard',
        'url' => route('company.dashboard'),
    ],


];


// ループで定義
foreach ($breadcrumbs as $bc) {
    Breadcrumbs::for($bc['name'], function ($trail) use ($bc) {
        if (!empty($bc['parent'])) {
            $trail->parent($bc['parent']);
        }
        $trail->push($bc['label'], $bc['url']);
    });
}