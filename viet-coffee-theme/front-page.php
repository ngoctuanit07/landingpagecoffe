<?php get_header(); ?>

<!-- HERO BANNER -->
<section class="hero-bg h-screen flex items-center text-white pt-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h1 class="text-5xl md:text-7xl font-bold heading-font mb-6 leading-tight">
            <?php echo get_theme_mod('hero_title', 'AUTHENTIC VIETNAMESE COFFEE'); ?>
        </h1>
        <p class="text-xl md:text-2xl mb-10 max-w-2xl mx-auto">
            <?php echo get_theme_mod('hero_subtitle', 'Single-origin beans from Vietnam\'s finest highlands. Freshly roasted with passion and delivered worldwide.'); ?>
        </p>
        <a href="#products" 
           class="inline-flex items-center gap-3 px-10 py-5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-2xl text-lg transition-all">
            SHOP OUR COLLECTION
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- FEATURED PRODUCT -->
<section class="py-20 bg-white" id="featured">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="inline-block px-6 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">FEATURED</span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#4A2C1A] mt-4">Signature Selection</h2>
        </div>
        
        <?php
        $featured_id = get_option('viet_coffee_options')['featured_product_id'] ?? 0;
        if ( $featured_id && class_exists('WooCommerce') ) {
            $product = wc_get_product( $featured_id );
            if ( $product ) {
                ?>
                <div class="max-w-2xl mx-auto">
                    <div class="coffee-card bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-xl">
                        <div class="h-96 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center p-8">
                            <?php echo $product->get_image( 'coffee-featured' ); ?>
                        </div>
                        <div class="p-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-3xl font-bold"><?php echo $product->get_name(); ?></h3>
                                    <p class="text-amber-700 mt-1"><?php echo wc_get_product_category_list( $product->get_id() ); ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-4xl font-bold"><?php echo $product->get_price_html(); ?></span>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6"><?php echo $product->get_short_description(); ?></p>
                            <button onclick="addToCartWoo(<?php echo $product->get_id(); ?>)" 
                                    class="w-full bg-[#4A2C1A] hover:bg-black text-white py-5 rounded-2xl font-semibold flex items-center justify-center gap-3 text-lg">
                                <i class="fa-solid fa-cart-plus"></i>
                                ADD TO CART
                            </button>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</section>

<!-- ALL PRODUCTS -->
<section class="py-20 bg-[#f8f1e9]" id="products">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl heading-font font-bold text-[#4A2C1A]">Our Collection</h2>
                <p class="text-gray-600">Exceptional Vietnamese coffees</p>
            </div>
        </div>

        <?php
        if ( class_exists( 'WooCommerce' ) ) {
            echo do_shortcode( '[products limit="5" columns="5" orderby="date" order="DESC"]' );
        } else {
            // Fallback
            echo '<p>WooCommerce not active.</p>';
        }
        ?>
    </div>
</section>

<!-- OUR STORY -->
<section class="py-20 bg-white" id="stories">
    <div class="max-w-7xl mx-auto px-6">
        <?php
        $story_page = get_page_by_path( 'our-story' );
        if ( $story_page ) {
            echo apply_filters( 'the_content', $story_page->post_content );
        } else {
            // Default content
            ?>
            <div class="grid md:grid-cols-12 gap-12 items-center">
                <div class="md:col-span-5">
                    <h2 class="text-4xl heading-font font-bold text-[#4A2C1A] mb-6">From Vietnam's Highlands to Your Cup</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        We work directly with farmers in Da Lat, Buon Ma Thuot and other renowned regions 
                        to bring you the freshest, highest quality Vietnamese coffee beans.
                    </p>
                </div>
                <!-- Add more dynamic or static -->
            </div>
            <?php
        }
        ?>
    </div>
</section>

<!-- CONTACT FORM -->
<section id="contact" class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="inline-block px-6 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">GET IN TOUCH</span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#4A2C1A] mt-4">Contact Us</h2>
        </div>
        <?php
        if ( shortcode_exists( 'contact-form-7' ) ) {
            echo do_shortcode( '[contact-form-7 id="your-form-id"]' ); // Replace with actual form ID
        } else {
            // Fallback form or note
            echo '<p>Please install Contact Form 7 or similar.</p>';
        }
        ?>
    </div>
</section>

<?php get_footer(); ?>