<?php
    GLOBAL $wp_rewrite;
    $paginate_base = get_pagenum_link(1);

    if(strpos($paginate_base, '?') || ! $wp_rewrite->using_permalinks()){
        $paginate_format = '';
        $paginate_base = add_query_arg('paged','%#%');

    } else{
        $paginate_format = (substr($paginate_base, -1, 1) == '/' ? '' : '/') .
        user_trailingslashit('page/%#%/','paged');;
        $paginate_base .= '%_%';
    }
?>
<div class="p-wp-pagination my-4">
    <?php
        echo paginate_links(array(
            'base' => $paginate_base,
            'format' => $paginate_format,
            'total' => $wp_query->max_num_pages,
            'mid_size' => 2,
            'current' => ($paged ? $paged : 1),
            'prev_text' => '<i class="fas fa-chevron-left"></i>',
            'next_text' => '<i class="fas fa-chevron-right"></i>',
        ));
    ?>
</div>
