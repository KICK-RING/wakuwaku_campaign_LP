<?php
/**
 * addPostReview
 *
 * 各種 query_var の設定
 * SALON HACK 用
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       2.0
**/

$reviewPostErrors = [];
global $reviewPostErrors;


add_action('template_redirect', 'saveReviewPostByFront');

function saveReviewPostByFront()
{
    if (isset($_POST['_wpnonce'])
        && wp_verify_nonce($_POST['_wpnonce'], 'review_post')){

        global $reviewPostErrors;

        /**
         * エラーチェック
        **/
        // 総合評価
        if(!isset($_POST['rate'])
            || empty($_POST['rate'])){
            $reviewPostErrors[] = '評価が未入力です';
        }

        // お名前
        if(!isset($_POST['user-name'])
            || empty($_POST['user-name'])){
            $reviewPostErrors[] = 'お名前が未入力です';
        }

        // 口コミ
        if(!isset($_POST['review'])
            || empty($_POST['review'])){
            $reviewPostErrors[] = '口コミが未入力です';
        }

        // 一言で言うと
        if(!isset($_POST['post_title'])
            || empty($_POST['post_title'])){
            $reviewPostErrors[] = '一言で言うとが未入力です';
        }

        if (empty($reviewPostErrors)) {

            /**
             * 口コミの保存
            **/
            $result = wp_insert_post([
                'post_title'   => esc_attr($_POST['post_title']),
                //'post_content' => htmlspecialchars($_POST['post_content']),
                'post_author'  => $_POST['post_author'],
                'post_status'  => 'publish',
                'post_type'    => 'review',
            ], true);

            if (!is_wp_error($result)) {

                /**
                 * 口コミのカスタムフィールド保存
                **/
                update_post_meta($result, 'rate', $_POST['rate']);
                update_post_meta($result, 'userName', htmlspecialchars($_POST['user-name']));
                update_post_meta($result, 'review', htmlspecialchars($_POST['review']));

                /**
                 * メール送信
                **/
                $to = get_the_author_meta('email', $_POST['post_author']);
                $subject = sprintf('%sさんからウェブサイトへ口コミがありました。', $_POST['user-name']);
                $message = sprintf("ウェブサイトの管理画面より、口コミを確認してください。\r\n\r\n%s/wp-admin/", get_bloginfo('url'));
                wp_mail($to, $subject, $message);

                /**
                 * スタッフのクチコミ情報を更新
                **/
                //dd($_POST['post_author']);
                //updateReviewSummary($_POST['post_author']);
                updateStaffReviewSummary($_POST['post_author']);
                updateSalonReviewSummary($_POST['post_author']);

                /**
                 * リダイレクト
                **/
                header('Location: ' . dirname($_POST['_wp_http_referer']));
                die;

            } else {
                $reviewPostErrors[] = 'エラーが発生しました' . $result->get_error_message();
            }
        }
    }
}





/**
 * スタッフのクチコミ情報を更新
**/
function updateStaffReviewSummary($post_author)
{
    // スタッフページ取得
    $staffPage = get_posts([
        'post_type'      => 'page',
        'name'           => get_the_author_meta('user_login', $post_author),
        'posts_per_page' => 1,
    ])[0];

    // スタッフの口コミ取得
    $reviews = get_posts([
        'author'         => $post_author,
        'post_type'      => 'review',
        'posts_per_page' => -1,
    ]);

    $reviewCount = count($reviews);

    if ($reviewCount === 0) {
        return;
    }

    $ratingValue = 0;

    $reviewValueSum = 0;
    $reviewRatingCount = [
        5 => 0,
        4 => 0,
        3 => 0,
        2 => 0,
        1 => 0,
    ];

    foreach ($reviews as $review) {
        $rate = (int)get_post_meta($review->ID, 'rate', true);
        //var_dump($rate);
        //dd($review);
        $reviewValueSum += $rate;
        $reviewRatingCount[$rate]++;
    }

    $ratingValue = round($reviewValueSum / $reviewCount, 2, PHP_ROUND_HALF_UP);

    update_post_meta($staffPage->ID, 'reviewCount', $reviewCount);
    update_post_meta($staffPage->ID, 'ratingValue', $ratingValue);
    update_post_meta($staffPage->ID, 'reviewRatingCount', serialize($reviewRatingCount));
}

/**
 * サロンのクチコミ情報を更新
**/
function updateSalonReviewSummary($post_author)
{
    // スタッフページ取得
    $staffPage = get_posts([
        'post_type'      => 'page',
        'name'           => get_the_author_meta('user_login', $post_author),
        'posts_per_page' => 1,
    ])[0];

    $staffListPage = get_page($staffPage->post_parent);
    $salonPage = get_page($staffListPage->post_parent);
    $author = [];

    $staffs = get_posts([
        'post_parent'    => $staffListPage->ID,
        'post_type'      => 'page',
        'posts_per_page' => -1,
    ]);

    foreach ($staffs as $staff) {
        $author[] = $staff->post_author;
    }
    $author[] = $salonPage->post_author;

    $reviews = get_posts([
        'author'         => $author,
        'post_type'      => 'review',
        'posts_per_page' => -1,
    ]);

    //dd($reviews);

    $reviewCount = count($reviews);
    $ratingValue = 0;

    $reviewValueSum = 0;
    $reviewRatingCount = [
        5 => 0,
        4 => 0,
        3 => 0,
        2 => 0,
        1 => 0,
    ];

    foreach ($reviews as $review) {
        $rate = (int)get_post_meta($review->ID, 'rate', true);
        $reviewValueSum += $rate;
        $reviewRatingCount[$rate]++;
    }

    $ratingValue = round($reviewValueSum / $reviewCount, 2, PHP_ROUND_HALF_UP);

    update_post_meta($salonPage->ID, 'reviewCount', $reviewCount);
    update_post_meta($salonPage->ID, 'ratingValue', $ratingValue);
    update_post_meta($salonPage->ID, 'reviewRatingCount', serialize($reviewRatingCount));
}

/**
 * エラ〜メッセージの表示
**/
function showReviewErrorMessages()
{
    global $reviewPostErrors;

    if (!empty($reviewPostErrors)) {
        $buffer = '<ul class="form-message error">';

        foreach ($reviewPostErrors as $error) {
            $buffer .= sprintf('<li>%s</li>', $error);
        }
        $buffer .= '</ul>';

        echo $buffer;
    }
}

/**
 * 管理画面からの投稿時
**/
add_action('save_post', function($post_ID, $post)
{
    if ($post === NULL
        || wp_is_post_revision($post_ID)
        || $post->post_type !== 'review'
        || empty($_POST)) {

        return;
    };

    //updateReviewSummary($post->post_author);
    updateStaffReviewSummary($post->post_author);
    updateSalonReviewSummary($post->post_author);

}, 100, 2);
