<?php
return [
    /**
     * updateOption
     *
    **/
    'updateOption' => [
        'blogdescription' => '',
        'comment_whitelist' => 1,
        'comments_notify' => 0,
        'default_comment_status' => 'closed',
        'default_pingback_flag' => 0,
        'default_ping_status' => 'closed',
        'default_role' => 'author',
        'image_default_align' => 'center',
        'image_default_link_type' => 'none',
        'moderation_notify' => 0,
        'permalink_structure' => '/media/%post_id%/',
        'require_name_email' => 0,
        'thread_comments' => 0,
        'large_size_w' => 1200,
        'large_size_h' => 1200,
        'medium_size_w' => 728,
        'medium_size_h' => 728,
        'thumbnail_size_w' => 1200,
        'thumbnail_size_h' => 630,
    ],

    /**
     * function
     *
    **/
    'function' => [
        'addCountViews' => true,
    ],
    
    /**
     * admin
     *
    **/
    'admin' => [
        'addScripts' => true,
        'addEditorStyle' => true,
        'removeDashboardContents' => true,
        'removeComment' => true,
        'removeRevision' => true,
    ],

    /**
     * head
     * <head>内に表示される各種タグの表示・表示設定
     * デフォルトは全て true
    **/
    'head' => [
        //'removeBasic' => false,
        //'removeDnsPrefetch' => false,
        //'removeJetpackCss' => false,
        //'removeTypeAttribute' => false,
        //'removeWpEmbedJs' => false,
        //'removeWpEmoji' => false,
    ],

    /**
     * front
     * ページに表示される内容
    **/
    /*
    'front' => [
        //'addYoutubeContainer' => false,
        //'excerptLength' => 50,
        //'excerptMore' => '',
        //'removeAdminBar' => false,
        //'removeBlogCard' => false,
    ],
    */

    /**
     * plugin
     * プラグイン
    **/
    /*
    'plugin' => [
        'updateTinyMceAdvanced' => false,
    ],
    */
];
