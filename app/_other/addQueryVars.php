<?php
/**
 * addQueryVars
 *
 * 各種 query_var の設定
 * SALON HACK 用
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       2.0
**/

add_action('template_redirect', function()
{
    global
        $post,
        $author,
        $salonPages,
        $breadcrumbs,
        $authors;

    if (is_page()) {
        setPageQueryVars($post);

    } elseif (is_singular()) {
        setSingularQueryVars($post);

    } elseif (is_404()) {
        $breadcrumbs[''] = '404 Not Found';
        $breadcrumbs[get_bloginfo('url')] = '<i class="fas fa-home"></i> ホーム';

    } else {

        if (is_category() || is_tag() || is_tax()) {
            $term = get_term(get_queried_object_id());
            setTermBreadcrumbs($term);
        }

        $postType = get_post_type_object(get_post_type());
        $breadcrumbs[get_post_type_archive_link($postType->name)] = $postType->label;
        $breadcrumbs[get_bloginfo('url')] = '<i class="fas fa-home"></i> ホーム';
    }

    /**
     * $salonPages
    **/
    if ($salon !== null) {
        $salonPages = get_pages([
            'parent'      => $salon->ID,
            'sort_column' => 'menu_order',
        ]);
    }

    /**
     * $breadcrumbs
    **/
    $breadcrumbs = array_reverse($breadcrumbs);

    /**
     * set
    **/
    set_query_var('salon',       $salon);
    set_query_var('salonPages',  $salonPages);
    set_query_var('staff',       $staff);
    set_query_var('breadcrumbs', $breadcrumbs);
    set_query_var('author' ,     $author);
    set_query_var('authors',     $authors);
});


function setPageQueryVars($post)
{
    global
        $author,
        $salonPages,
        $breadcrumbs,
        $authors;

    /**
     * $author
    **/
    $author = get_userdata($post->post_author);

    $authorPage = get_posts([
        'name'      => get_the_author_meta('user_login', $post->post_author),
        'post_type' => 'page',
    ])[0];

    if (get_page_template_slug($authorPage->ID) === 'template-staff.php') {
        $staffPage         = $authorPage;
        $author->staffID   = $staffPage->ID;
        $author->staffURL  = get_permalink($staffPage->ID);
        $author->staffSlug = $staffPage->post_name;
        $author->avatar    = wp_get_attachment_image_src($author->image, 'cropped-thumb')[0];

        /*
        $author->staff = new blank();
        $author->staff->ID     = $staffPage->ID;
        $author->staff->url    = get_permalink($staffPage->ID);
        $author->staff->slug   = $staffPage->post_name;
        $author->staff->avatar = wp_get_attachment_image_src($author->image, 'cropped-thumb')[0];
        */

        $staffListPage = get_page($authorPage->post_parent);
        $salonPage = get_page($staffListPage->post_parent);

        $author->salonID       = $salonPage->ID;
        $author->salonURL      = get_permalink($salonPage->ID);
        $author->salonSlug     = $salonPage->post_name;
        $author->salonName     = $salonPage->post_title;
        $author->salonNameKana = get_post_meta($salonPage->ID,  'nameKana', true);
        $author->salonThumb    = get_the_post_thumbnail_url($salonPage->ID, 'cropped-thumb');
        $author->salonLogo     = wp_get_attachment_image_url(get_post_meta($salonPage->ID,  'logo', true), 'medium');
        $author->salonTel      = get_post_meta($salonPage->ID, 'tel', true);
        $author->salonReserve  = get_post_meta($salonPage->ID, 'reserve', true);

        /*
        $author->salon = new blank();
        $author->salon->ID       = $salonPage->ID;
        $author->salon->url      = get_permalink($salonPage->ID);
        $author->salon->slug     = $salonPage->post_name;
        $author->salon->name     = $salonPage->post_title;
        $author->salon->nameKana = get_post_meta($salonPage->ID,  'nameKana', true);
        $author->salon->thumb    = get_the_post_thumbnail_url($salonPage->ID, 'cropped-thumb');
        $author->salon->logo     = wp_get_attachment_image_url(get_post_meta($salonPage->ID,  'logo', true), 'medium');
        $author->salon->tel      = get_post_meta($salonPage->ID, 'tel', true);
        $author->salon->reserve  = get_post_meta($salonPage->ID, 'reserve', true);
        */

    } elseif (get_page_template_slug($authorPage->ID) === 'template-salon.php') {
        $salonPage             = $authorPage;
        $author->salonID       = $salonPage->ID;
        $author->salonURL      = get_permalink($salonPage->ID);
        $author->salonSlug     = $salonPage->post_name;
        $author->salonName     = $salonPage->post_title;
        $author->salonNameKana = get_post_meta($salonPage->ID,  'nameKana', true);
        $author->salonThumb    = get_the_post_thumbnail_url($salonPage->ID, 'cropped-thumb');

        $author->salonLogo     = wp_get_attachment_image_url(get_post_meta($salonPage->ID,  'logo', true), 'medium');
        $author->salonTel      = get_post_meta($salonPage->ID, 'tel', true);
        $author->salonReserve  = get_post_meta($salonPage->ID, 'reserve', true);

        $salonPages = get_posts([
            'post_type'   => 'page',
            'post_parent' => $salonPage->ID,
        ]);
    }

    /**
     * $breadcrumbs
    **/
    setPageBreadcrumbs($post);
    $breadcrumbs[get_bloginfo('url')] = '<i class="fas fa-home"></i> ホーム';

    /**
     * $authors
    **/
    if ($staffPage === null && $authors === null) {
        $staffListPage = get_posts([
            'post_parent' => $salonPage->ID,
            'name'        => 'staff',
            'post_type'   => 'page',
        ])[0];

        $staffList = get_pages([
            'parent' => $staffListPage->ID,
            'sort_column' => 'menu_order',
        ]);

        foreach ($staffList as $staffListItem) {
            $authors[] = $staffListItem->post_author;
            //$authors[] = get_the_author_meta('ID', $author->salonID)
        }
        $authors[] = $salonPage->post_author;

    } else {
        $authors[] = $staffPage->post_author;
    }

}

function setPageBreadcrumbs($post)
{
    global $breadcrumbs;

    $breadcrumbs[get_the_permalink($post->ID)] = $post->post_title;

    if ($post->post_parent !== 0) {
        $parent = get_page($post->post_parent);
        setPageBreadcrumbs($parent);
    }
}

function setSingularQueryVars($post)
{
    global
        $author,
        $breadcrumbs;

    /**
     * $author
    **/
    $author = get_userdata($post->post_author);

    $staffPage = get_posts([
        'name'      => get_the_author_meta('user_login', $post->post_author),
        'post_type' => 'page',
    ])[0];
    $staffListPage = get_page($staffPage->post_parent);

    if ($staffListPage->post_parent !== 0) {
        $salonPage = get_page($staffListPage->post_parent);

    } elseif ($salon === null) {
        $salonPage = get_page(get_option('page_on_front'));
    }

    $author->staffID       = $staffPage->ID;
    $author->staffURL      = get_permalink($staffPage->ID);
    $author->avatar        = wp_get_attachment_image_src($author->image, 'cropped-thumb')[0];
    $author->salonID       = $salonPage->ID;
    $author->salonURL      = get_permalink($salonPage->ID);
    $author->salonName     = $salonPage->post_title;
    $author->salonNameKana = get_post_meta($salonPage->ID,  'nameKana', true);
    $author->salonTel      = get_post_meta($salonPage->ID, 'tel', true);
    $author->salonReserve  = get_post_meta($salonPage->ID, 'reserve', true);
    //dd($author);

    /**
     * $breadcrumbs
    **/
    $postType = get_post_type_object(get_post_type());

    $breadcrumbs[get_the_permalink($post->ID)] = $post->post_title;
    $breadcrumbs[get_the_permalink($staffPage->ID) . $postType->name] = $postType->label;
    $breadcrumbs[get_the_permalink($staffPage->ID)] = $staffPage->post_title;
    $breadcrumbs[get_the_permalink($staffListPage->ID)] = $staffListPage->post_title;
    $breadcrumbs[get_the_permalink($salonPage->ID)] = $salonPage->post_title;
    $breadcrumbs[get_bloginfo('url')] = '<i class="fas fa-home"></i> ホーム';
}

function setArchiveQueryVars($term)
{
    global $breadcrumbs;

    $breadcrumbs[get_term_link($term->term_id)] = $term->name;

    if ($term->parent !== 0) {
        $parent = get_term($term->parent);
        setArchiveQueryVars($parent);
    }

}

function setTermBreadcrumbs($term)
{
    global $breadcrumbs;

    $breadcrumbs[get_term_link($term->term_id)] = $term->name;

    if ($term->parent !== 0) {
        setTermBreadcrumbs(get_term($term->parent));
    }
}



class blank
{
}
