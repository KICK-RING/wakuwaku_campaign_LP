<?php
/**
 * updateMenu
 *
 * menuの表示内容変更
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

add_action('admin_menu', function()
{
    /**
     * 管理者以外のメニュー削除
    **/
    if (!current_user_can('administrator')) {
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
    }
});
