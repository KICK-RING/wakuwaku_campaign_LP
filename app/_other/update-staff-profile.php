<?php

add_action('profile_update', function()
{
    $user = wp_get_current_user();
    $staffPage = get_posts([
        'post_type' => 'page',
        'name'      => $user->user_login,
    ])[0];
    $staffPage->post_title = $user->name;

    wp_update_post($staffPage);

    $imageID = get_user_meta($user->ID, 'image')[0];

    if (get_post_meta($staffPage->ID, '_thumbnail_id', true) !== $imageID) {
        update_post_meta($staffPage->ID, '_thumbnail_id', $imageID);
    }
});

add_filter('pre_user_display_name', function($name)
{
    return $_POST['name'];
});
