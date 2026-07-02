<?php get_header(); 

$show_featured     = get_theme_mod( 'show_featured', 1 );
$show_products     = get_theme_mod( 'show_products', 1 );
$show_stories      = get_theme_mod( 'show_stories', 1 );
$show_testimonials = get_theme_mod( 'show_testimonials', 1 );
$show_contact      = get_theme_mod( 'show_contact', 1 );
$products_count    = absint( get_theme_mod( 'products_per_page', 6 ) );
$products_cat      = get_theme_mod( 'products_category', '' );
?>

<!-- HERO BANNER -->
<section class="hero-bg h-screen flex items-center text-white pt-20">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h1 class="text-5xl md:text-7xl font-bold heading-font mb-6 leading-tight">
            <?php echo esc_html( get_theme_mod( 'hero_title', 'AUTHENTIC VIETNAMESE COFFEE' ) ); ?>
        </h1>
        <p class="hero-subtitle text-xl md:text-2xl mb-10 max-w-2xl mx-auto">
            <?php echo esc_html( get_theme_mod( 'hero_subtitle', 'Single-origin beans from Vietnam\'s finest highlands. Freshly roasted with passion and delivered worldwide.' ) ); ?>
        </p>
        <a href="<?php echo esc_attr( get_theme_mod( 'hero_button_link', '#products' ) ); ?>" 
           class="inline-flex items-center gap-3 px-10 py-5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-2xl text-lg transition-all">
            <?php echo esc_html( get_theme_mod( 'hero_button_text', 'SHOP OUR COLLECTION' ) ); ?>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- TRUST / PROFESSIONAL BAR -->
<section class="py-4 bg-white border-b">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 text-center text-sm">
            <?php 
            if ( function_exists( 'viet_coffee_get_trust_items' ) ) {
                foreach ( viet_coffee_get_trust_items() as $item ) {
                    echo '<div class="flex items-center justify-center gap-2 text-[#4A2C1A]"><i class="fa-solid ' . esc_attr( $item['icon'] ) . '"></i> <span class="font-medium">' . esc_html( $item['text'] ) . '</span></div>';
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCT -->
<?php if ( $show_featured ) : ?>
<section class="py-20 bg-white" id="featured">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="inline-block px-6 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-medium"><?php esc_html_e( 'FEATURED', 'viet-coffee' ); ?></span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#4A2C1A] mt-4"><?php esc_html_e( 'Signature Selection', 'viet-coffee' ); ?></h2>
        </div>
        
        <?php
        $featured_id = isset( get_option( 'viet_coffee_options' )['featured_product_id'] ) ? get_option( 'viet_coffee_options' )['featured_product_id'] : 0;
        if ( $featured_id && class_exists( 'WooCommerce' ) ) {
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
                                    <h3 class="text-3xl font-bold"><?php echo esc_html( $product->get_name() ); ?></h3>
                                    <p class="text-amber-700 mt-1"><?php echo wc_get_product_category_list( $product->get_id() ); ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-4xl font-bold"><?php echo $product->get_price_html(); ?></span>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6"><?php echo wp_kses_post( $product->get_short_description() ); ?></p>

                            <div class="flex gap-3">
                                <button data-add-to-cart="<?php echo esc_attr( $product->get_id() ); ?>" 
                                        class="flex-1 bg-[#4A2C1A] hover:bg-black text-white py-4 rounded-2xl font-semibold flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-cart-plus"></i> <?php esc_html_e( 'Add to Cart', 'viet-coffee' ); ?>
                                </button>
                                <button onclick="buyNow(<?php echo esc_attr( $product->get_id() ); ?>, '<?php echo esc_js( $product->get_name() ); ?>', '<?php echo esc_js( strip_tags( $product->get_price_html() ) ); ?>');"
                                        class="flex-1 border border-[#4A2C1A] text-[#4A2C1A] hover:bg-[#4A2C1A] hover:text-white py-4 rounded-2xl font-semibold">
                                    <?php esc_html_e( 'Buy Now', 'viet-coffee' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p class="text-center text-gray-500">' . esc_html__( 'Set a Featured Product ID in Viet Coffee admin or via Customizer.', 'viet-coffee' ) . '</p>';
        }
        ?>
    </div>
</section>
<?php endif; ?>

<!-- ALL PRODUCTS -->
<?php if ( $show_products ) : ?>
<section class="py-20 bg-[#f8f1e9]" id="products">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10">
            <div>
                <h2 class="text-4xl heading-font font-bold text-[#4A2C1A]"><?php esc_html_e( 'Our Collection', 'viet-coffee' ); ?></h2>
                <p class="text-gray-600"><?php esc_html_e( 'Exceptional Vietnamese coffees', 'viet-coffee' ); ?></p>
            </div>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="mt-3 md:mt-0 inline-flex items-center gap-2 text-sm font-medium text-[#4A2C1A] hover:underline">
                <?php esc_html_e( 'View full shop →', 'viet-coffee' ); ?>
            </a>
        </div>

        <?php
        if ( class_exists( 'WooCommerce' ) ) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => max( 3, min( 12, $products_count ) ),
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            if ( ! empty( $products_cat ) ) {
                $args['tax_query'] = array( array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $products_cat ),
                ) );
            }
            $product_query = new WP_Query( $args );

            if ( $product_query->have_posts() ) {
                echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">';
                while ( $product_query->have_posts() ) {
                    $product_query->the_post();
                    global $product;
                    $product = wc_get_product( get_the_ID() );
                    wc_get_template_part( 'template-parts/content', 'product' );
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p class="text-gray-500">' . esc_html__( 'No products found. Import demo data or add products in WooCommerce.', 'viet-coffee' ) . '</p>';
            }
        } else {
            echo '<p>' . esc_html__( 'WooCommerce is required for the shop.', 'viet-coffee' ) . '</p>';
        }
        ?>
    </div>
</section>
<?php endif; ?>

<!-- OUR STORY -->
<?php if ( $show_stories ) : ?>
<section class="py-20 bg-white" id="stories">
    <div class="max-w-7xl mx-auto px-6">
        <?php
        $story_page = get_page_by_path( 'our-story' );
        if ( $story_page ) {
            echo apply_filters( 'the_content', $story_page->post_content );
        } else {
            ?>
            <div class="max-w-4xl mx-auto text-center">
                <span class="inline-block px-5 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium tracking-wider">OUR HERITAGE</span>
                <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#4A2C1A] mt-4 mb-6"><?php esc_html_e( 'From Vietnam Highlands to Your Cup', 'viet-coffee' ); ?></h2>
                <p class="text-lg text-gray-600"><?php esc_html_e( 'Use the Import Demo button in the Viet Coffee admin panel or edit a page called "our-story".', 'viet-coffee' ); ?></p>
            </div>
            <?php
        }
        ?>
    </div>
</section>
<?php endif; ?>

<!-- TESTIMONIALS -->
<?php if ( $show_testimonials ) : ?>
<section class="py-20 bg-[#f8f1e9]" id="testimonials">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="inline-block px-6 py-2 bg-white text-amber-800 rounded-full text-sm font-medium"><?php esc_html_e( 'LOVED BY COFFEE LOVERS', 'viet-coffee' ); ?></span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#4A2C1A] mt-4"><?php esc_html_e( 'What Our Customers Say', 'viet-coffee' ); ?></h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <?php for ( $i = 1; $i <= 3; $i++ ) :
                $quote = get_theme_mod( "testimonial_{$i}_quote", '' );
                $name  = get_theme_mod( "testimonial_{$i}_name", '' );
                $role  = get_theme_mod( "testimonial_{$i}_role", '' );
                if ( ! $quote && $i === 1 ) {
                    $quote = 'Cà phê ngon nhất tôi từng thử. Hương vị đậm đà và giao hàng rất nhanh.';
                    $name = 'Nguyễn Thị Lan';
                    $role = 'Coffee enthusiast, Hà Nội';
                }
                if ( $quote ) : ?>
                    <div class="bg-white rounded-3xl p-8 shadow">
                        <div class="text-3xl text-amber-400 mb-3">“</div>
                        <p class="text-gray-700 mb-6 italic"><?php echo esc_html( $quote ); ?></p>
                        <div>
                            <div class="font-semibold"><?php echo esc_html( $name ); ?></div>
                            <?php if ( $role ) : ?>
                                <div class="text-sm text-gray-500"><?php echo esc_html( $role ); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif;
            endfor; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CONTACT -->
<?php if ( $show_contact ) : ?>
<section id="contact" class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-10">
            <span class="inline-block px-6 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-medium"><?php esc_html_e( 'GET IN TOUCH', 'viet-coffee' ); ?></span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#4A2C1A] mt-4"><?php esc_html_e( 'Contact Us', 'viet-coffee' ); ?></h2>
            <p class="text-gray-600 mt-3"><?php esc_html_e( 'Questions about orders, wholesale, or our coffees? We\'d love to hear from you.', 'viet-coffee' ); ?></p>
        </div>

        <div class="max-w-xl mx-auto">
            <?php 
            // Simple native contact form (pro feature - works without plugins)
            if ( function_exists( 'viet_coffee_render_contact_form' ) ) {
                viet_coffee_render_contact_form();
            } elseif ( shortcode_exists( 'contact-form-7' ) ) {
                echo do_shortcode( '[contact-form-7 id="1"]' ); // user can change
            } else {
                // Fallback message + link to Customizer
                echo '<div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center">';
                echo '<p class="mb-3">' . esc_html__( 'Install Contact Form 7 or use the built-in form.', 'viet-coffee' ) . '</p>';
                echo '<a href="' . esc_url( admin_url( 'customize.php' ) ) . '" class="text-[#4A2C1A] underline">' . esc_html__( 'Or edit contact details in Customizer → Contact & Social', 'viet-coffee' ) . '</a>';
                echo '</div>';
            }
            ?>

            <div class="mt-10 text-sm text-center text-gray-600 space-y-1">
                <?php 
                $email = get_theme_mod( 'contact_email', 'hello@vietcoffee.com' );
                $phone = get_theme_mod( 'contact_phone', '' );
                $addr  = get_theme_mod( 'contact_address', 'Buôn Ma Thuột, Vietnam' );
                ?>
                <?php if ( $email ) : ?><div><strong><?php esc_html_e( 'Email:', 'viet-coffee' ); ?></strong> <a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-[#4A2C1A]"><?php echo esc_html( $email ); ?></a></div><?php endif; ?>
                <?php if ( $phone ) : ?><div><strong><?php esc_html_e( 'Phone:', 'viet-coffee' ); ?></strong> <?php echo esc_html( $phone ); ?></div><?php endif; ?>
                <?php if ( $addr ) : ?><div><?php echo esc_html( $addr ); ?></div><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>