<?php
/**
 * updateUserContactmethods
 *
 * ユーザー情報の内容変更
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

add_filter('user_contactmethods', function($user_contactmethods)
{
    //$buffer['name']      = '表示名';
    //$buffer['name_kana'] = '表示名（カナ）';
    //$buffer['position']  = '役職';
    //$buffer['reserve']   = '指名予約URL';
    $buffer['twitter']   = 'twitter ユーザー名（@なし）';
    $buffer['instagram'] = 'instagram アカウント ID';
    //$buffer['line']      = 'LINE ID';
    $buffer['facebook']  = 'facebook URL';
    $buffer['youtube']   = 'YouTube チャンネル URL';
    $buffer['niconico']  = 'ニコニコ動画 URL';

    return $buffer;
});
