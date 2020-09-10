<?php
/**
 * addAutosaveStyleThumbnail
 *
 * スタイルのアイキャッチ自動更新
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

add_action('save_post', function($post_ID, $post)
{
    if ($post === NULL
        || wp_is_post_revision($post_ID)
        || $post->post_type !== 'style') {

        return;
    };

    // images を取得
    $images = get_post_meta($post_ID, 'images');

    // images の一つ目をサムネイルに登録
    update_post_meta($post_ID, '_thumbnail_id', $images[0][0]);

}, 100, 2);
