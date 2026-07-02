<?php
/**
 * The main template file
 *
 * @package Viet_Coffee
 */

get_header();

if ( is_front_page() ) {
    // Redirect to front-page.php
    get_template_part( 'front-page' );
} elseif ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'template-parts/content', get_post_type() );
    }
} else {
    ?>
    <main id="primary" class="site-main">
        <section class="no-results not-found">
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'viet-coffee' ); ?></p>
        </section>
    </main>
    <?php
}

get_footer();
