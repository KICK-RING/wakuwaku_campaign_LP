<?php
/**
 * showEnqueueScripts
 *
 * エンキューされているcss/jsを表示する
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
**/

add_action('wp_print_styles', function()
{
    global $wp_styles;

    echo "<!-- WP_Dependencies for styles\n";

    foreach ($wp_styles->queue as $val) {
        echo getDependency($wp_styles->registered[$val]);
    }
    echo "-->\n";

}, 9999);

add_action('wp_print_scripts', function()
{
    global $wp_scripts;

    echo "<!-- WP_Dependencies for scripts\n";

    foreach ($wp_scripts->queue as $val) {
        echo getDependency($wp_scripts->registered[$val]);
    }
    echo "-->\n";

}, 9999);

function getDependency($dependency)
{
    $dep = "";

    if ( is_a( $dependency, "_WP_Dependency" ) ) {
        $dep .= "$dependency->handle";
        $dep .= " [" . implode( " ", $dependency->deps ) . "]";
        $dep .= " '$dependency->src'";
        $dep .= " '$dependency->ver'";
        $dep .= " '$dependency->args'";
        $dep .= " (" . implode( " ", $dependency->extra ) . ")";
    }

    return "$dep\n";
}
