<?php
/**
 * updateLabelPostToBlog
 *
 * 管理画面の「投稿」を「ブログ」に変更
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       2.0
**/

add_action('init', function()
{
    global $wp_post_types;

    $wp_post_types['post']->label                            = 'ブログ';
    $wp_post_types['post']->labels->name                     = 'ブログ';
    $wp_post_types['post']->labels->singular_name            = 'ブログ';
    $wp_post_types['post']->labels->add_new_item             = '新規ブログを追加';
    $wp_post_types['post']->labels->edit_item                = 'ブログの編集';
    $wp_post_types['post']->labels->new_item                 = '新規ブログ';
    $wp_post_types['post']->labels->view_item                = 'ブログを表示';
    $wp_post_types['post']->labels->view_items               = 'ブログの表示';
    $wp_post_types['post']->labels->search_items             = 'ブログを検索';
    $wp_post_types['post']->labels->not_found                = 'ブログが見つかりませんでした。';
    $wp_post_types['post']->labels->not_found_in_trash       = 'ゴミ箱内にブログが見つかりませんでした。';
    $wp_post_types['post']->labels->all_items                = 'ブログ一覧';
    $wp_post_types['post']->labels->archives                 = 'ブログアーカイブ';
    $wp_post_types['post']->labels->attributes               = 'ブログの属性';
    $wp_post_types['post']->labels->insert_into_item         = 'ブログに挿入';
    $wp_post_types['post']->labels->uploaded_to_this_item    = 'このブログへのアップロード';
    $wp_post_types['post']->labels->filter_items_list        = 'ブログリストの絞り込み';
    $wp_post_types['post']->labels->items_list_navigation    = 'ブログリストナビゲーション';
    $wp_post_types['post']->labels->items_list               = 'ブログリスト';
    $wp_post_types['post']->labels->item_published           = 'ブログを公開しました。';
    $wp_post_types['post']->labels->item_published_privately = 'ブログを限定公開しました。';
    $wp_post_types['post']->labels->item_reverted_to_draft   = 'ブログを下書きに戻しました。';
    $wp_post_types['post']->labels->item_scheduled           = 'ブログを予約しました。';
    $wp_post_types['post']->labels->item_updated             = 'ブログを更新しました。';
    $wp_post_types['post']->labels->menu_name                = 'ブログ';
    $wp_post_types['post']->labels->name_admin_bar           = 'ブログ';
});
