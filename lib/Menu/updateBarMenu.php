<?php
/**
 * updateBarMenu
 *
 * bar menuの表示内容変更
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

add_action('admin_bar_menu', function($wp_admin_bar)
{
    /**
     * 管理者以外のメニュー削除
    **/
    if (!current_user_can('administrator')) {
        $wp_admin_bar->remove_menu('wp-logo');
    }

}, 99);
