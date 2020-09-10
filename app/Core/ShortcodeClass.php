<?php
/**
 * Shortcode
 *
 * カスタム投稿タイプを追加するための親クラス
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       3.0
**/

Shortcode::getInstance();

class Shortcode extends Singleton
{
    /**
     * コンストラクタ
    **/
    public function initialize()
    {
        /**
         * ショートコード入力時の p, br タグの削除
        **/
        add_filter('the_content', function($content)
        {
            $array = [
                '<p>[' => '[',
                ']</p>' => ']',
                ']<br />' => ']'
            ];
            $content = strtr($content, $array);

            return $content;
        });

        /**
         * div
         * div で囲う汎用的なショートコード
        **/
        add_shortcode('div', function($atts, $content = null)
        {
            extract(shortcode_atts([
                'class' => null,
            ], $atts));

            return sprintf('<div class="%s">%s</div>', $class, do_shortcode($content));
        });

        /**
         * colwrap
         * div class="row" で囲うグリッド用のショートコード
        **/
        add_shortcode('row', function($atts, $content = null)
        {
            return sprintf('<div class="row">%s</div>', do_shortcode($content));
        });

        /**
         * col
         * div で囲うグリッド用のショートコード
        **/
        add_shortcode('col', function($atts, $content = null)
        {
            extract(shortcode_atts([
                'class' => null,
            ], $atts));

            return sprintf('<div class="%s">%s</div>', $class, do_shortcode($content));
        });

        /**
         * related
         * 関連記事を表示するショートコード
        **/
        add_shortcode('related', function($atts)
        {
            extract(shortcode_atts([
                'id' => null,
                'target' => null,
            ], $atts));

            // 記事情報の取得
            $url = get_permalink($id);
            $thumbnail = get_the_post_thumbnail($id, 'thumbnail');
            $title = get_the_title($id);

            // URL がなかったら非表示
            if (!$url) {
                return null;
            }

            if ($target !== null) {
                $target = sprintf('target="%s"', $target);
            }

            if ($rel !== null) {
                $rel = sprintf('rel="%s"', $rel);
            }

            $buffer = sprintf(
                '<div class="p-related-post">
                    <a class="p-related-post__link" href="%s" %s>
                        <figure class="p-related-post__thumb">%s</figure>
                        <div class="p-related-post__title">%s</div>
                    </a>
                </div>',
                $url, $target, $thumbnail, $title
            );

            return $buffer;
        });

        /**
         * speech
         * div で囲うグリッド用のショートコード
        **/
        add_shortcode('speech', function($atts, $content = null)
        {
            $icon = sprintf('%s/asset/img/no-image-icon.png', get_template_directory_uri());
            extract(shortcode_atts([
                'icon' => $icon,
                'name' => '名前',
                'direction' => 'left',
                'class' => '',
            ], $atts));

            $buffer = sprintf(
                '<div class="p-shortcode clearfix %s">
                    <figure class="p-shortcode__eyecatch">
                        <img src="%s" alt="%s" />
                        <figcaption class="p-shortcode__eyecatch-caption">%s</figcaption>
                    </figure>
                    <div class="p-shortcode__message">%s</div>
                </div>',
                $atts['class'], $atts['icon'], $atts['name'], $atts['name'], $content
            );

            return $buffer;
        });
    }
}
