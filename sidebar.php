<?php
    $wppHtml = sprintf('
        <div class="p-post-list__simple">
            <a class="p-post-list__simple-link text-body" href="{url}">
                <figure class="p-post-list__simple-thumb">
                    <img class="js-lazy-image" src="%s" data-src="{thumb_url}" alt="">
                </figure>
                <div class="p-post-list__simple-title">
                    {text_title}
                </div>
            </a>
        </div>',
        image('dummy-image.jpg'));

    $wpp = [
        'header'            => '',
        'header_start'      => '',
        'header_end'        => '',
        'limit'             => 10,
        'range'             => 'all',
        'freshness'         => 0,
        'order_by'          => 'views',
        'post_type'         => 'post',
        'pid'               => '',
        'cat'               => '',
        'author'            => '',
        'title_length'      => 100,
        //'excerpt_length'    => 60,
        'thumbnail_width'   => 600,
        'thumbnail_height'  => 315,
        'stats_comments'    => 0,
        'stats_views'       => 0,
        'stats_author'      => 0,
        'stats_date'        => 0,
        //'stats_date_format' => 'Y.m.d',
        'stats_category'    => 0,
        'wpp_start'         => '',
        'wpp_end'           => '',
        'post_html'         => $wppHtml,
    ];

?>

<aside class="l-aside">
    <div class="container">

        <div class="p-ads__aside-top mb-4">
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6653389343465986"
                 data-ad-slot="2120075073"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>
                 (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>

        <?php if (function_exists('wpp_get_mostpopular')): ?>
            <div class="p-post-widget mb-4">
                <div class="p-post-widget__heading">
                    人気の記事
                </div>
                <div class="p-post-widget__content">
                    <div class="p-post-widget__post-list p-post-list--aside-widget">
                        <?php wpp_get_mostpopular($wpp); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>


        <?php
            $arg = [
                'posts_per_page' => 10,
            ];
            $new_posts = get_posts($arg);

            if ($new_posts):
        ?>
            <div class="p-post-widget mb-4">
                <div class="p-post-widget__heading">
                    最新の記事
                </div>
                <div class="p-post-widget__content">
                    <div class="p-post-widget__post-list p-post-list--aside-widget">
                        <?php
                            foreach ($new_posts as $post) {
                                setup_postdata($post);
                                echo get_template_part('_post-list__simple');
                            }
                            wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="p-ads__aside-bottom mb-4">
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6653389343465986"
                 data-ad-slot="9643341879"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>
                 (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>

        <div class="p-post-widget mb-4">
            <div class="p-post-widget__heading">
                カテゴリー
            </div>
            <div class="p-post-widget__content">
                <div class="p-post-widget__category list-group">
                    <?php
                        $categories = get_categories([
                            //'parent' => 0,
                            'orderby' => 'menu_order',
                            'pad_counts' => true,
                        ]);

                        foreach ($categories as $category):

                            if ($category->parent === 0):
                    ?>
                        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= get_term_link($category->term_id) ?>">
                            <?= $category->name ?>
                            <span class="badge badge-pill"><?= $category->count ?></span>
                        </a>
                    <?php
                                if (get_term_children($category->term_id, 'category')):
                                    $childCategories = get_categories([
                                        'parent' => $category->term_id,
                                        'orderby' => 'menu_order',
                                        'pad_counts' => true,
                                    ]);

                                    foreach ($childCategories as $childCategory):
                    ?>
                        <a class="p-post-widget__category-child list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= get_term_link($childCategory->term_id) ?>">
                            <?= $childCategory->name ?>
                            <span class="badge badge-secondary badge-pill"><?= $childCategory->count ?></span>
                        </a>
                    <?php
                                    endforeach;
                                endif;
                            endif;
                        endforeach;
                    ?>
                </div>
            </div>
        </div>

    </div>
</aside>
