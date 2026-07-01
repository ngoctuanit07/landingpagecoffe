<?php
/**
 * Single Product custom layout
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="max-w-7xl mx-auto px-6 py-12">
    <?php
    while ( have_posts() ) :
        the_post();
        wc_get_template_part( 'content', 'single-product' );
    endwhile;
    ?>
</div>

<?php get_footer( 'shop' ); ?>