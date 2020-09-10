<?php
/**
* addAutosavePostThumbnail
*
* 投稿のアイキャッチ自動更新
*
* @copyright   Copyright (c) 2015 Sinciate Inc.
* @link        https://sinciate.co.jp
* @since       1.0
**/

add_action('save_post', function($post_ID, $post)
{
    global $wpdb;

    if ($post === NULL
        || wp_is_post_revision($post_ID)
        || $post->post_type !== 'post'
        || get_post_meta($post_ID, '_thumbnail_id', true)
        || get_post_meta($post_ID, 'skip_post_thumb', true)) {

        return;
    };

    $post = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE id = $post_ID");

    //正規表現にマッチしたイメージのリストを格納する変数の初期化
    $matches = array();

    //投稿本文からすべての画像を取得
    preg_match_all('/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'>]*)/i', $post[0]->post_content, $matches);

    //YouTubeのサムネイルを取得（画像がなかった場合）
    if (empty($matches[0])) {
        preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $post[0]->post_content, $match);
        if (!empty($match[1])) {
            $matches=array();
            $matches[0]=$matches[1]=array('http://img.youtube.com/vi/'.$match[1].'/mqdefault.jpg');
        }
    }

    if (count($matches)) {

        foreach ($matches[0] as $key => $image) {
            //画像がイメージギャラリーにあったなら、サムネイルIDをCSSクラスに追加（イメージタグからIDを探す）
            preg_match('/wp-image-([\d]*)/i', $image, $thumb_id);
            $thumb_id = $thumb_id[1];

            //サムネイルが見つからなかったら、データベースから探す
            if (!$thumb_id) {
                $image = substr($image, strpos($image, '"')+1);
                $result = $wpdb->get_results("SELECT ID FROM {$wpdb->posts} WHERE guid = '".$image."'");
                $thumb_id = $result[0]->ID;
            }

            //それでもサムネイルIDが見つからなかったら、画像をURLから取得する
            if (!$thumb_id) {
                $thumb_id = fetch_thumbnail_image($matches, $key, $post[0]->post_content, $post_ID);
            }

            //サムネイルの取得に成功したらPost Metaをアップデート
            if ($thumb_id) {
                update_post_meta($post_ID, '_thumbnail_id', $thumb_id);
                break;
            }
        }
    }

}, 100, 2);
