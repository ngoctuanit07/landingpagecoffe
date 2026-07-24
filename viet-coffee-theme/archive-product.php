<?php
/**
 * Custom Product Archive
 */
get_header(); ?>

<main id="main-content"><section class="pt-32 pb-20 bg-[#f8f1e9]">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-4xl heading-font font-bold text-[#4A2C1A] mb-8">Our Collection</h1>
        <?php if ( woocommerce_product_loop() ) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php wc_get_template_part( 'template-parts/content', 'product' ); ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section></main>

<?php get_footer(); ?>
