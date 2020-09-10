<?php
get_header();
the_post();

$slug = $post->post_name;
$catch = str_replace(['-', '_'], ' ', $slug);

?>

<div class="p-page-<?= $slug ?> p-page">

    <header class="p-page__header">
        <div class="p-page__header-bg">
            <canvas class="p-canvas__moving-circle" id="js-moving-cirlce-canvas"></canvas>
        </div>
        <div class="container">
            <h1 class="p-page__title">
                <?= the_title() ?>
            </h1>
            <div class="p-page__title-en">
                <?= $catch ?>
            </div>
        </div>
    </header>

    <div class="p-page__body">
        <div class="container">
            <div class="p-page__content p-wp-editor">
                <?= the_content() ?>
            </div>
        </div>
    </div>

</div>

<?php get_footer();
