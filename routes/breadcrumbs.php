<?php

use Diglactic\Breadcrumbs\Breadcrumbs;


if (!app()->runningInConsole()) {

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


        [
            'parent' => 'manager.dashboard',
            'name' => 'manager.master.m_employer',
            'label' => '利用会社情報一覧',
            'url' => route('manager.master.m_employer'),
        ],

        [
            'parent' => 'manager.master.m_employer',
            'name' => 'manager.master.m_employer.entry',
            'label' => '利用会社情報編集',
            'url' => route('manager.master.m_employer.entry'),
        ],

        [
            'parent' => 'manager.dashboard',
            'name' => 'manager.master.m_employer_user',
            'label' => '利用会社ユーザー情報一覧',
            'url' => route('manager.master.m_employer_user'),
        ],

        [
            'parent' => 'manager.dashboard',
            'name' => 'manager.master.m_license',
            'label' => '資格・免許情報一覧',
            'url' => route('manager.master.m_license'),
        ],


        [
            'parent' => 'manager.master.m_license',
            'name' => 'manager.master.m_license.entry',
            'label' => '資格・免許情報編集',
            'url' => route('manager.master.m_license.entry'),
        ],

        // 利用会社start
        // ダッシュボード
        [
            'parent' => null,
            'name' => 'employer.dashboard',
            'label' => 'DashBoard',
            'url' => route('employer.dashboard'),
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

}