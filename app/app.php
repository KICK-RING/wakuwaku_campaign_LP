<?php
/**
 * app
 *
 * アプリケーションの基本クラス
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

App::getInstance();

class App extends Singleton
{
    public $viewsCountKey = 'views';
    public $config = [
        'updateOption' => [
        ],
        'function' => [
            'addCountViews' => false,
        ],
        'admin' => [
            'addScripts' => false,
            'addEditorStyle' => false,
            'removeDashboardContents' => false,
            'removeComment' => false,
            'removeRevision' => false,
        ],
        'head' => [
            'removeBasic' => true,
            'removeDnsPrefetch' => true,
            'removeJetpackCss' => true,
            'removeTypeAttribute' => true,
            'removeWpEmbedJs' => true,
            'removeWpEmoji' => true,
        ],
        'front' => [
            'addYoutubeContainer' => true,
            'excerptLength' => 100,
            'excerptMore' => '...',
            'removeAdminBar' => true,
            'removeBlogCard' => true,
        ],
        'plugin' => [
            'updateTinyMceAdvanced' => true,
        ],
    ];

    /**
     * コンストラクタ
    **/
    public function initialize()
    {
        /**
         * 設定のマージ
        **/
        $config = require(THEME_DIR . '/config/app.php');

        foreach ($config as $category => $array) {

            foreach ($array as $key => $value) {
                $this->config[$category][$key] = $value;

            }
        }


        /**
         * updateOption
         * テーマ変更時に、description が空じゃない時のみ
        **/
        add_action('after_switch_theme', function()
        {
            if (get_option('blogdescription') !== '') {

                foreach ($this->config['updateOption'] as $key => $value) {
                    update_option($key, $value);
                }
            }
        });

        /**
         * function
        **/
        if ($this->config['function']['addCountViews']) {
            $this->addCountViews();
        }

        /**
         * admin
        **/
        if ($this->config['admin']['addScripts']) {
            $this->addScripts();
        }

        if ($this->config['admin']['addEditorStyle']) {
            add_editor_style(get_template_directory_uri() . '/asset/css/editor.min.css');
        }

        if ($this->config['admin']['removeDashboardContents']) {
            $this->removeDashboardContents();
        }

        if ($this->config['admin']['removeComment']) {
            $this->removeComment();
        }

        if ($this->config['admin']['removeRevision']) {
            add_filter('wp_print_scripts', function($columns) {
                wp_deregister_script('autosave');
            });
        }

        /**
         * head
        **/
        if ($this->config['head']['removeBasic']) {
            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'wlwmanifest_link');
            remove_action('wp_head', 'wp_generator');
        }

        if ($this->config['head']['removeDnsPrefetch']) {
            $this->removeDnsPrefetch();
        }

        if ($this->config['head']['removeJetpackCss']) {
            add_filter('jetpack_implode_frontend_css', '__return_false');
        }

        if ($this->config['head']['removeTypeAttribute']) {
            $this->removeTypeAttribute();
        }

        if ($this->config['head']['removeWpEmbedJs']) {
            remove_action('wp_head', 'rest_output_link_wp_head');
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
            remove_action('wp_head', 'wp_oembed_add_host_js');
        }

        if ($this->config['head']['removeWpEmoji']) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles' );
            remove_action('admin_print_styles', 'print_emoji_styles');
        }

        /**
         * front
        **/
        if ($this->config['front']['addYoutubeContainer']) {
            add_action('the_content', function($content) {
                if (is_singular()) {
                    $content = preg_replace('/<iframe[^>]+?youtube\.com[^<]+?<\/iframe>/is', '<div class="u-embed-youtube">${0}</div>', $content);
                }
                return $content;
            });
        }

        if ($this->config['front']['excerptLength']) {
            add_filter('excerpt_length', function() {
                return $this->config['front']['excerptLength'];
            }, 999);
        }

        if ($this->config['front']['excerptMore']) {
            add_filter('excerpt_more', function() {
                return $this->config['front']['excerptMore'];
            }, 999);
        }

        if ($this->config['front']['removeAdminBar']) {
            add_filter('show_admin_bar', '__return_false');
        }

        if ($this->config['front']['removeBlogCard']) {
            remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result');
        }

        /**
         * plugin
        **/
        if ($this->config['plugin']['updateTinyMceAdvanced']) {
            $this->updateTinyMceAdvanced();
        }
    }

    /**
     * addCountViews()
    **/
    public function addCountViews()
    {
        add_action('template_redirect', function()
        {
            global $post;

            if (is_singular()) {
                $count = get_post_meta($post->ID, $this->viewsCountKey, true);

                if ($count !== '') {
                    update_post_meta($post->ID, $this->viewsCountKey, (int)++$count);

                } else {
                    $count = 1;
                    delete_post_meta($post->ID, $this->viewsCountKey);
                    add_post_meta($post->ID, $this->viewsCountKey, (int)$count);
                }
            }
        });

        add_action('transition_post_status', function($new_status, $old_status, $post)
        {
            if ($new_status === 'publish') {
                $count = (int)get_post_meta($post->ID, $key, true);

                if (!empty($count)) {
                    update_post_meta($post->ID, $this->viewsCountKey, (int)++$count);

                } else {
                    $count = 1;
                    add_post_meta($post->ID, $this->viewsCountKey, (int)$count);
                }
            }

        }, 10, 3);
    }

    /**
     * addScripts()
    **/
    public function addScripts()
    {
        add_action('admin_enqueue_scripts', function()
        {
            $cssFileName = 'admin.min.css';
            $jsFileName = 'admin.min.js';

            $cssFile = get_template_directory_uri()  . '/asset/css/admin.min.css';
            $jsFile = get_template_directory_uri()  . '/asset/js/admin.min.js';

            $cssFileModified = filemtime(sprintf('%s/asset/css/%s', get_template_directory(), $cssFileName));
            $jsFileModified = filemtime(sprintf('%s/asset/js/%s', get_template_directory(), $jsFileName));

            wp_enqueue_style('app-admin-style', $cssFile . '?' . $cssFileModified);
            wp_enqueue_script('app-admin-script', $jsFile . '?' . $jsFileModified, ['jquery']);

        });
    }

    /**
     * removeDashboardContents()
    **/
    public function removeDashboardContents()
    {
        add_action('wp_dashboard_setup', function()
        {
            global $wp_meta_boxes;

            // WordPress イベントとニュース
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

            // クイックドラフト
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

            // 今人気 (Wordpress Popular Posts)
            unset($wp_meta_boxes['dashboard']['normal']['core']['wpp_trending_dashboard_widget']);

            // Yoast SEO 投稿の概要
            unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);

        }, 999);
    }

    /**
     * removeComment()
    **/
    public function removeComment()
    {
        // admin bar menu
        add_action('admin_bar_menu', function($wp_admin_bar)
        {
            $wp_admin_bar->remove_menu('comments');
        }, 99);

        // menu
        add_action('admin_menu', function()
        {
            remove_menu_page('edit-comments.php');
        });

        // post & page list column
        add_filter('manage_posts_columns', function($columns)
        {
            unset($columns['comments']);
            return $columns;
        }, 100);
    }

    /**
     * removeDnsPrefetch()
     * remove dns-prefetch from head
    **/
    public function removeDnsPrefetch()
    {
        add_filter('wp_resource_hints', function($hints, $relation_type)
        {
            if ('dns-prefetch' === $relation_type) {
                return array_diff(wp_dependencies_unique_hosts(), $hints);
            }

            return $hints;

        }, 10, 2);
    }

    /**
     * removeTypeAttribute()
     * remove basic from head
    **/
    public function removeTypeAttribute()
    {
        add_action('template_redirect', function()
        {
            ob_start(function($buffer){
                $buffer = str_replace(['<script type="text/javascript"', "<script type='text/javascript'"], '<script', $buffer);
                $buffer = str_replace(['<style type="text/css"', "<style type='text/css'"], '<style', $buffer);
                return $buffer;
            });
        });

        add_filter('script_loader_tag', function($tag)
        {
            $tag = str_replace([' type="text/javascript"', " type='text/javascript'"], '', $tag);
            return $tag;
        });

        add_filter('style_loader_tag', function($tag)
        {
            $tag = str_replace([' type="text/css"', " type='text/css'"], '', $tag);
            return $tag;
        });
    }

    /**
     * updateTinyMceAdvanced()
    **/
    public function updateTinyMceAdvanced()
    {
        add_filter('tiny_mce_before_init', function($config)
        {
            $invalidStyles = [
                'table' => 'width height',
                'th' => 'width height',
                'td' => 'width height',
            ];

            $config['block_formats'] = '段落=p; 見出し2=h2; 見出し3=h3; 見出し4=h4;';
            $config['fontsize_formats'] = '75% 100% 125% 150% 200% 250% 300%';
            $config['table_resize_bars'] = false;
            $config['invalid_styles'] = json_encode($invalidStyles);
            $config['object_resizing'] = 'img';
            $config['cache_suffix'] = 'v=' . time();

            return $config;

        }, 10, 2);
    }
}
