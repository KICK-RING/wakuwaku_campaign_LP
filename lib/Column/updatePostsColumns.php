<?php
/**
 * updatePostsColumns
 *
 * 投稿一覧画面の表示内容の変更
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       2.0
**/

/**
 * manage_post_posts_columns
 *
 * カラムの追加と削除・表示順の変更
**/
add_filter('manage_works_posts_columns', function($columns)
{
    // サムネイルの追加
    $columns['thumbnail'] = __('Thumbnail');

    // 更新日の追加
    //$columns['modified'] = '更新日';

    // ソート変更
    $sort_number = [
        'cb'         => 0,
        'title'      => 2,
        'thumbnail'  => 1,
        //'author'     => 3,
        //'categories' => 4,
        //'tags'       => 5,
        'date'       => 6,
        //'modified'   => 7,
    ];
    $sort = array();

    foreach($columns as $key => $value){
        $sort[] = $sort_number{$key};
    }
    array_multisort($sort,$columns);

    return $columns;

}, 100);

/**
 * manage_post_posts_custom_column
 *
 * 各カラムがあればデータを表示
**/
add_filter('manage_works_posts_custom_column', function($column_name, $post_id)
{
    // サムネイル画像の追加
    if ('thumbnail' == $column_name) {
        echo get_the_post_thumbnail($post_id, 'cropped-thumb');
    }

}, 10, 2);
