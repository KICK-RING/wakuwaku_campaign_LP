<?php
/***************************************
 * sinciate
 *
 * @copyright Sinciate Inc.
 * @link      https://sinciate.co.jp
 * @update    2020.02.26
***************************************/

//ini_set('display_errors', 1);


/***************************************
 * アプリケーションの読み込み
***************************************/

require_once 'app/index.php';

/***************************************
 * ライブラリの読み込み
***************************************/

// Custom Post Type
// require(LIB_DIR . DS . 'CustomPostType' . DS . 'member.php');

// Taxonomy

// Column

// Module


/***************************************
 * その他設定
***************************************/

// eyecatch
add_theme_support('post-thumbnails', ['member']);
add_image_size('small-thumb', 600, 315, true);
add_image_size('cropped-thumb', 400, 400, true);


/***************************************
 * enqueueScripts
***************************************/

// Show Enqueue Scripts
//require(LIB_DIR . DS . 'showEnqueueScripts.php');

add_action('wp_enqueue_scripts', function()
{
    // css dequeue
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('contact-form-7');

    //wp_dequeue_style('yarppWidgetCss');
    //wp_dequeue_style('yarppRelatedCss');
    //wp_dequeue_style('ez-toc');
    //wp_dequeue_style('wordpress-popular-posts-css');

    //wp_dequeue_style('jetpack-widget-social-icons-styles');

    // css enqueue
    wp_enqueue_style('googlefonts', '//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@900&display=swap', [], null);
    //wp_enqueue_style('adobe-fonts', '//use.typekit.net/uta6hes.css', [], null);

    //wp_enqueue_style('fontawesome-cdn', '//use.fontawesome.com/releases/v5.2.0/css/all.css', [], null);
    //wp_enqueue_style('bootstrap-cdn', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', [], null);
    wp_enqueue_style('app', css('app'), [], null);


    // js dequeue
    //wp_deregister_script('admin-bar');
    //wp_deregister_script('jquery');
    wp_deregister_script('ez-toc-js');

    // js enqueue
    wp_enqueue_script('jquery');
    // wp_enqueue_script('jquery-cdn', '//code.jquery.com/jquery-3.4.1.min.js', [], null);
    //wp_enqueue_script('popper-cdn', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', [], null, true);
    wp_enqueue_script('bootstrap-cdn', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', [], null, true);
    // wp_enqueue_script('slick-cdn', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', [], null, true);
    //wp_enqueue_script('featherlight-cdn', '//cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.13/featherlight.min.js', [], null, true);
    //wp_enqueue_script('barba-cdn', '//cdnjs.cloudflare.com/ajax/libs/barba.js/1.0.0/barba.js', [], null, true);
    // wp_enqueue_script('barba-v2-cdn', '//cdn.jsdelivr.net/npm/@barba/core@2.9.7/dist/barba.umd.min.js', [], null, true);
    wp_enqueue_script('gsap-cdn', '//unpkg.com/gsap@latest/dist/gsap.min.js', [], null, true);

    //wp_enqueue_script('popper', js('vendor/popper'), [], null, true);
    //wp_enqueue_script('bootstrap', js('vendor/bootstrap'), [], null, true);

    // wp_enqueue_script('slick', js('vendor/slick'), [], null, true);
    //wp_enqueue_script('featherlight', js('vendor/featherlight'), [], null, true);

    wp_enqueue_script('polyfill-intersection-observer', js('vendor/polyfill.intersection-observer'), [], null, true);
    //wp_enqueue_script('google-adsense', '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js', [], null, true);

    wp_enqueue_script('app', js('app'), [], null, true);

}, 999);

/**
 * jetpack css の削除
**/
add_filter('jetpack_implode_frontend_css','__return_false' );
