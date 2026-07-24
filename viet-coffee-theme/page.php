<?php
/**
 * Standard page template, including WooCommerce cart and checkout pages.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" class="site-main max-w-7xl mx-auto px-6 py-28">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ( ! function_exists( 'is_cart' ) || ( ! is_cart() && ! is_checkout() && ! is_account_page() ) ) : ?>
                <h1 class="entry-title text-4xl heading-font font-bold mb-8"><?php the_title(); ?></h1>
            <?php endif; ?>
            <div class="entry-content"><?php the_content(); ?></div>
        </article>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
