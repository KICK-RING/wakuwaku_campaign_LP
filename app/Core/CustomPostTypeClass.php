<?php
/**
 * CustomPostType
 *
 * カスタム投稿タイプを追加するための親クラス
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

class CustomPostType extends Singleton
{
    /**
     * デフォルト設定
    **/
    public $slug;
    public $default = [
        //'label'                 => $slug,
        //'labels'                => $slug, // same as 'label'
        //'description'           => ''
        //'public'                => false,
        //'exclude_from_search'   => true, // different as 'public'
        //'publicly_queryable'    => false, // same as 'public'
        //'show_ui'               => false, // same as 'public'
        //'show_in_nav_menus'     => false, // same as 'public'
        //'show_in_menu'          => false, // same as 'show_ui'
        //'show_in_admin_bar'     => false, // same as 'show_in_menu'
        //'menu_position'         => null,
        //'menu_icon'             => null,
        //'capability_type'       => 'post',
        //'capabilities'          => , // same as 'capability_type'
        //'map_meta_cap'          => null,
        //'hierarchical'          => false,
        //'supports'              => ['title', 'editor'],
        //'register_meta_box_cb'  => ,
        //'taxonomies'            => ,
        //'has_archive'           => false,
        //'permalink_epmask'      => EP_PERMALINK,
        //'rewrite'               => true,
        //'query_var'             => true,
        //'can_export'            => true,
        //'_bulitin'              => false,
        //'_edit_link'            => ,

        'show_in_rest'          => true,
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ];
    public $preGetPosts = [];
    public $linkType = 'ID';

    /**
     * コンストラクタ
    **/
    public function initialize()
    {
        // $settingの反映
        $this->setting = array_merge($this->default, $this->setting);
        $this->setting['rest_base'] = $this->slug;

        // ラベルの設定
        $this->setLabels();

        // カスタム投稿タイプの登録
        add_action('init', [$this, 'registerPostType']);

        // クエリの変更
        if (count($this->preGetPosts) !== 0) {
            add_action('pre_get_posts', [$this, 'preGetPosts']);
        }

        // リンクURLの変更
        add_filter('post_type_link', [$this, 'postTypeLink'], 1, 2);

        // リライトルールの変更
        add_filter('rewrite_rules_array', [$this, 'rewriteRulesArray']);
    }

    /**
     * ラベルの設定
    **/
    private function setLabels()
    {
        $this->setting['labels'] = [
            'name'               => $this->setting['label'],
            'singular_name'      => $this->setting['label'],
            'menu_name'          => $this->setting['label'],
            'name_admin_bar'     => $this->setting['label'],
            'all_items'          => sprintf('%s一覧', $this->setting['label']),
            'add_new'            => '新規追加',
            'add_new_item'       => sprintf('%sの新規追加', $this->setting['label']),
            'edit_item'          => sprintf('%sの編集', $this->setting['label']),
            'new_item'           => sprintf('%sの新規追加', $this->setting['label']),
            'view_item'          => sprintf('%sを表示', $this->setting['label']),
            'search_items'       => sprintf('%sを検索', $this->setting['label']),
            'not_found'          => sprintf('%sが見つかりません', $this->setting['label']),
            'not_found_in_trash' => sprintf('ゴミ箱に%sが見つかりません', $this->setting['label']),
            'parent_item_colon'  => sprintf('親：%s', $this->setting['label']),
        ];
    }

    /**
     * カスタム投稿タイプの登録
    **/
    public function registerPostType()
    {
        register_post_type($this->slug, $this->setting);
    }

    /**
     * クエリの変更
     */
    public function preGetPosts($query)
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        if (is_post_type_archive($this->slug)) {

            foreach ($this->preGetPosts as $key => $value) {
                $query->set($key, $value);
            }
        }
    }

    /**
     * リンク表示の設定
     */
    public function postTypeLink($link, $post)
    {
        if ($post->post_type === $this->slug) {

            if ($this->linkType === 'ID') {
                $linkType = $post->ID;

            } elseif ($this->linkType === 'slug') {
                $linkType = $post->post_name;
            }

            $link = sprintf('%s/%s/%s/', get_bloginfo('url'), $this->slug, $linkType);
        }

        return $link;
    }

    /**
     * リライトルールの設定
     */
    public function rewriteRulesArray($rules)
    {
        // paged
        $regex    = sprintf('%s/page/([0-9!-~]+)/?$', $this->slug);
        $redirect = sprintf('index.php?post_type=%s&paged=$matches[1]', $this->slug);
        $newRules[$regex] = $redirect;

        // single
        if ($this->linkType === 'ID') {
            $regex    = sprintf('%s/([0-9!-~]+)/?$', $this->slug);
            $redirect = sprintf('index.php?post_type=%s&p=$matches[1]', $this->slug);

        } elseif ($this->linkType === 'slug') {
            $regex    = sprintf('%s/([^/]+)/?$', $this->slug);
            $redirect = sprintf('index.php?post_type=%s&name=$matches[1]', $this->slug);

        } else {
        }
        $newRules[$regex] = $redirect;

        return $newRules + $rules;
    }
}
