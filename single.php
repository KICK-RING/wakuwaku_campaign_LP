<?php
    get_header();
    the_post();

    // $categories
    $categories = get_the_category();

    // $tags
    $tags = get_the_tags();

    // wpp views
    $views = function_exists('wpp_get_views')? wpp_get_views(get_the_ID(), 'all'): null;

    // 関連記事
    $relatedPosts = get_posts([
        'category' => $categories[0]->term_id,
        'exclude' => $post->ID,
        'orderby' => 'rand',
        'posts_per_page' => 10,
    ]);
?>

<div class="p-page__conts">
    <div class="p-single-post">
        <div class="p-single-post__ttl p-ttl">
            <?= get_the_title() ?>
        </div>
        <div class="p-single-post__cont">
            <?= the_content() ?>
        </div>
        <div class="p-single-post__pagination">
            <?php if (get_previous_post()):?>
                <div class="p-single-post__pagination-item p-single-post__pagination-prev">
                    <?php previous_post_link('%link', 'PREV'); ?>
                </div>
            <?php endif; ?>
            <div class="p-single-post__pagination-item p-single-post__pagination-back">
                <a href="<?= get_bloginfo('url') ?>/news/">
                    BACK TO LIST
                </a>
            </div>
            <?php if (get_next_post()):?>
                <div class="p-single-post__pagination-item p-single-post__pagination-next">
                    <?php next_post_link('%link', 'NEXT'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer();
