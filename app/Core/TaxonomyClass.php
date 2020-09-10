<?php
/**
 * Taxonomy
 *
 * タクソノミーを追加するための親クラス
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

class Taxonomy extends Singleton
{
    /**
     * デフォルト設定
    **/
    public $slug;
    public $default = [
        //'label'                 => $slug,
        //'labels'                => $slug, // same as 'label'
        //'public'                => true,
        //'show_ui'               => true, // default: same as 'public'
        //'show_in_nav_menus'     => true, // default: same as 'public'
        //'show_tagcloud'         => true, // default: same as 'show_ui'
        //'show_in_quick_edit'    => true, // default: same as 'show_ui'
        //'meta_box_cb'           => null,
        //'show_admin_column'     => false,
        //'description'           => '',
        //'hierarchical'          => false,
        //'update_count_callback' => ,
        //'query_var'             => $slug,
        //'rewrite'               => true,
        //'capabilities'          => ,
        //'sort'                  => ,
        //'_built_in'             => false,

        'show_in_rest'          => true,
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    ];
    public $postType;

    /**
     * コンストラクタ
    **/
    public function initialize()
    {
        // $settingの反映
        $this->setting = array_merge($this->default, $this->setting);

        // ラベルの設定
        $this->setLabels();

        // タクソノミーの登録
        add_action('init', [$this, 'registerTaxonomy']);

        // リライトルールの変更
        add_filter('rewrite_rules_array', [$this, 'rewriteRulesArray']);
    }

    /**
     * ポストタイプのラベル設定
     */
    public function setLabels()
    {
        $this->setting['labels'] = [
            'name'                       => $this->setting['label'],
            'singular_name'              => $this->setting['label'],
            'menu_name'                  => $this->setting['label'],
            'all_items'                  => sprintf('%s一覧', $this->setting['label']),
            'edit_item'                  => sprintf('%sの編集', $this->setting['label']),
            'view_item'                  => sprintf('%sを表示', $this->setting['label']),
            'update_item'                => sprintf('%sを更新', $this->setting['label']),
            'add_new_item'               => sprintf('%sの新規追加', $this->setting['label']),
            'new_item_name'              => sprintf('新しい%sの名前', $this->setting['label']),
            'parent_item'                => sprintf('親の%s', $this->setting['label']),
            'parent_item_colon'          => sprintf('親：%s', $this->setting['label']),
            'search_items'               => sprintf('%sを検索', $this->setting['label']),
            'popular_items'              => sprintf('人気の%s', $this->setting['label']),
            'separate_items_with_commas' => sprintf('%sをコンマで区切ってください', $this->setting['label']),
            'add_or_remove_items'        => sprintf('%sの追加または削除', $this->setting['label']),
            'choose_from_most_used'      => sprintf('よく使われている%sから選択', $this->setting['label']),
            'not_found'                  => sprintf('%sが見つかりません', $this->setting['label']),
        ];
    }

    /**
     * タクソノミーの登録
     */
    public function registerTaxonomy()
    {
        register_taxonomy($this->slug, $this->postType, $this->setting);
    }

    /**
     * リライトルールの設定
     */
    public function rewriteRulesArray($rules)
    {
        /**
         * paged archive
        **/
        $regex = sprintf('%s/%s/([^/]+)/page/([0-9!-~]+)/?$', $this->postType, $this->slug);
        $redirect = sprintf('index.php?taxonomy=%s&term=$matches[1]&paged=$matches[2]', $this->slug);
        $newRules[$regex] = $redirect;

        /**
         * archive
        **/
        $regex = sprintf('%s/%s/([^/]+)/?$', $this->postType, $this->slug);
        $redirect = sprintf('index.php?taxonomy=%s&term=$matches[1]', $this->slug);
        $newRules[$regex] = $redirect;

        return $newRules + $rules;
    }
}
