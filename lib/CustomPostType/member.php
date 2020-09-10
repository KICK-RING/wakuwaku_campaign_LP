<?php
/**
 * CustomPostTypeMember
 *
 * member のカスタム投稿タイプ
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

CustomPostTypeMember::getInstance();

class CustomPostTypeMember extends CustomPostType
{
    /**
     * 設定
    **/
    public $slug = 'member';
    public $setting = [
        'label'         => 'メンバー',
        'public'        => true,
        'menu_position' => 6,
        'menu_icon'     => 'dashicons-groups',
        'supports'      => ['title', 'editor', 'thumbnail'],
        //'taxonomies'    => [],
        'has_archive'   => true,
        'rewrite'       => ['with_front' => false],
    ];
    public $preGetPosts = [
        'posts_per_page' => -1,
    ];

    /**
     * コンストラクタ
    **/
    public function initialize()
    {
        parent::initialize();
    }
}
