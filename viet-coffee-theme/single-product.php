<?php
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
?>
<main id="main-content" class="site-main max-w-7xl mx-auto px-6 py-28">
    <?php
    while ( have_posts() ) {
        the_post();
        wc_get_template_part( 'content', 'single-product' );
    }
    ?>
</main>
<?php get_footer( 'shop' ); ?>
