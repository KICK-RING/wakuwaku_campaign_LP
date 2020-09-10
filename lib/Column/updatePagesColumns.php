<?php
/**
 * updatePagesColumns
 *
 * 固定ページ一覧画面の表示内容の変更
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       2.0
**/

/**
 * manage_pages_columns
 *
 * カラムの追加と削除・表示順の変更
**/
add_filter('manage_page_posts_columns', function($columns)
{
    // サムネイルの追加
    $columns['thumbnail'] = __('Thumbnail');

    // カラムの削除
    //unset($columns['author']);
    unset($columns['date']);
    unset($columns['wpseo-links']);
    unset($columns['wpseo-score']);
    unset($columns['wpseo-score-readability']);
    unset($columns['wpseo-focuskw']);

    // ソート変更
    $sort_number = [
        'cb'             => 0,
        'title'          => 1,
        'thumbnail'      => 2,
        'author'         => 3,
        'wpseo-title'    => 4,
        'wpseo-metadesc' => 5,
    ];
    $sort = array();

    foreach($columns as $key => $value){
        $sort[] = $sort_number{$key};
    }
    array_multisort($sort,$columns);

    return $columns;

}, 100);

/**
 * manage_page_posts_custom_column
 *
 * 各カラムがあればデータを表示
**/
add_filter('manage_page_posts_custom_column', function($column_name, $post_id)
{
    // サムネイル画像の追加
    if ('thumbnail' == $column_name) {
        echo get_the_post_thumbnail($post_id, 'cropped-thumb');
    }

}, 10, 2);
