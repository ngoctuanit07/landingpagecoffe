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
<?php
$hero_options = get_option( 'viet_coffee_options', array() );
$hero_ids = isset( $hero_options['hero_slider_ids'] ) ? array_filter( array_map( 'absint', (array) $hero_options['hero_slider_ids'] ) ) : array();
$hero_images = array();
foreach ( $hero_ids as $hero_id ) {
    $hero_url = wp_get_attachment_image_url( $hero_id, 'full' );
    if ( $hero_url ) { $hero_images[] = $hero_url; }
}
if ( ! $hero_images ) {
    $hero_images[] = get_theme_mod( 'hero_background', 'https://picsum.photos/id/1016/2000/1200' );
}
?>
<main id="main-content"><section class="hero-bg hero-slider min-h-screen" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Homepage hero', 'viet-coffee' ); ?>">
    <div class="hero-slides" aria-live="polite">
        <?php foreach ( $hero_images as $index => $hero_url ) : ?>
            <div class="hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url( $hero_url ); ?>')" role="group" aria-label="<?php echo esc_attr( sprintf( __( 'Slide %1$d of %2$d', 'viet-coffee' ), $index + 1, count( $hero_images ) ) ); ?>"></div>
        <?php endforeach; ?>
    </div>
    <div class="hero-overlay" aria-hidden="true"></div>
    <div class="hero-content">
        <h1><?php echo wp_kses_post( get_theme_mod( 'hero_title', 'AUTHENTIC VIETNAMESE COFFEE' ) ); ?></h1>
        <p class="hero-subtitle"><?php echo wp_kses_post( get_theme_mod( 'hero_subtitle', '' ) ); ?></p>
        <a class="hero-cta" href="<?php echo esc_url( get_theme_mod( 'hero_button_link', '#products' ) ); ?>"><?php echo esc_html( get_theme_mod( 'hero_button_text', 'SHOP OUR COLLECTION' ) ); ?></a>
    </div>
    <?php if ( count( $hero_images ) > 1 ) : ?>
        <button class="hero-control hero-prev" type="button" aria-label="<?php esc_attr_e( 'Previous slide', 'viet-coffee' ); ?>">&#10094;</button>
        <button class="hero-control hero-next" type="button" aria-label="<?php esc_attr_e( 'Next slide', 'viet-coffee' ); ?>">&#10095;</button>
        <div class="hero-dots"><?php foreach ( $hero_images as $index => $_ ) : ?><button type="button" class="<?php echo 0 === $index ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'viet-coffee' ), $index + 1 ) ); ?>"></button><?php endforeach; ?></div>
    <?php endif; ?>
</section>

<!-- SIGNATURE SELECTION -->
<?php if ( $show_featured ) : ?>
<section class="py-20 bg-white" id="featured">
    <div class="max-w-3xl mx-auto px-6 text-center">
<h2 class="text-4xl md:text-5xl heading-font font-bold text-[#3D2817] mt-6 mb-6" style="color: #3D2817;">Great cup of coffee shouldn't only belong behind expensive equipment.
</h2>
        <?php
        $sig_opts = get_option( 'viet_coffee_options', array() );
        $sig_text = isset( $sig_opts['signature_text'] ) ? $sig_opts['signature_text'] : '';
        if ( $sig_text ) {
            echo '<p class="text-lg text-[#64748B] leading-relaxed">' . nl2br( esc_html( $sig_text ) ) . '</p>';
        } else {
            echo '<p class="text-[#64748B] italic">' . esc_html__("Sometimes it's brewed in a hotel room before sunrise.
Sometimes beside a quiet lake.
Sometimes at your desk between meetings.
We created our Steeped Coffee for those moments.
No equipment.
No complicated brewing.Just hot water, a few quiet minutes, and a great cup of coffee—wherever life takes you.
", 'viet-coffee' ) . '</p>';
        }
        ?>
    </div>
</section>
<?php endif; ?>

<!-- ALL PRODUCTS -->
<?php if ( $show_products ) : ?>
<section class="py-20 bg-[#FBF8F3]" id="products">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12">
            <div>
                <h2 class="text-4xl heading-font font-bold text-[#3D2817]" style="color: #3D2817;">Discover A Fresh Perspective on Vietnamese Coffee With Us </h2>
                <p class="text-[#64748B] text-lg mt-2">Our farmers grow it. Our farmers process it. We craft it.</p>
            </div>
            <!-- <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="mt-6 md:mt-0 inline-flex items-center gap-2 text-sm font-semibold text-[#6B4423] hover:text-[#D4A574] transition-colors">
                <?php //esc_html_e( 'Xem tất cả →', 'viet-coffee' ); ?>
            </a> -->
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
                echo '<div class="text-center py-12"><p class="text-[#64748B] text-lg">' . esc_html__( 'No products yet. Please add products in WooCommerce.', 'viet-coffee' ) . '</p></div>';
            }
        } else {
            echo '<p class="text-center text-[#64748B]">' . esc_html__( 'WooCommerce is required for the shop.', 'viet-coffee' ) . '</p>';
        }
        ?>
    </div>
</section>
<?php endif; ?>

<!-- OUR STORY / CÂU CHUYỆN (lấy từ WordPress Page) -->
<?php if ( $show_stories ) : ?>
<?php
    $story_options     = get_option( 'viet_coffee_options', array() );
    $story_image       = isset( $story_options['story_image'] ) ? $story_options['story_image'] : 'https://picsum.photos/id/1016/1200/900';
    $story_content     = isset( $story_options['story_content'] ) ? $story_options['story_content'] : 'A journey from the highlands of Vietnam to your coffee cup. We are committed to bringing you the finest coffee beans with our daily fresh roasting process.';
    
?>
<section id="stories" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="inline-block px-6 py-2 bg-[#FBF8F3] text-[#D4A574] rounded-full text-sm font-medium tracking-wide" style="background-color: #FBF8F3; color: #D4A574;">OUR HERITAGE</span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#3D2817] mt-6 mb-4" style="color: #3D2817;">Our Story</h2>
        </div>
        
        <div class="grid md:grid-cols-2 gap-10 lg:gap-14 items-center">
            <!-- Image (Left) -->
            <div class="order-2 md:order-1">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl ring-1 ring-black/5">
                    <img 
                        src="<?php echo esc_url( $story_image ); ?>" 
                        alt="Câu chuyện của chúng tôi" 
                        class="w-full h-auto object-cover"
                        style="aspect-ratio: 4 / 3;"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#3D2817]/60 via-[#3D2817]/20 to-transparent"></div>
                </div>
            </div>
            
            <!-- Content (Right) -->
            <div class="order-1 md:order-2">
                <div class="story-rich-content text-[#2D2D2D]">
                    <?php echo apply_filters( 'the_content', $story_content ); ?>
                </div>

                <!-- Story highlights -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
                    <?php
                    for ( $i = 1; $i <= 3; $i++ ) {
                        $emoji = isset( $story_options[ "highlight_{$i}_emoji" ] ) ? $story_options[ "highlight_{$i}_emoji" ] : '';
                        $title = isset( $story_options[ "highlight_{$i}_title" ] ) ? $story_options[ "highlight_{$i}_title" ] : '';
                        $desc  = isset( $story_options[ "highlight_{$i}_desc" ] ) ? $story_options[ "highlight_{$i}_desc" ] : '';
                        
                        if ( ! empty( $emoji ) || ! empty( $title ) ) {
                            ?>
                            <div class="p-5 bg-[#FBF8F3] rounded-2xl border border-[#E8DCC9]/60 transition-all hover:-translate-y-0.5 hover:shadow-md">
                                <div class="text-2xl mb-2.5"><?php echo esc_html( $emoji ); ?></div>
                                <div class="font-semibold text-[#3D2817] mb-1"><?php echo esc_html( $title ); ?></div>
                                <p class="text-sm text-[#64748B]"><?php echo esc_html( $desc ); ?></p>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CONTACT -->
<?php if ( $show_contact ) : ?>
<section id="contact" class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-10">
            <span class="inline-block px-6 py-2 bg-[#FBF8F3] text-[#D4A574] rounded-full text-sm font-medium tracking-wide" style="background-color: #FBF8F3; color: #D4A574;">✉️ CONTACT</span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#3D2817] mt-6" style="color: #3D2817;">Get In Touch</h2>
            <p class="text-[#64748B] mt-3">Have questions? We'd love to hear from you.</p>
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
                echo '<div class="bg-[#FBF8F3] border border-[#E8DCC9] rounded-2xl p-8 text-center">';
                echo '<p class="mb-3 text-[#64748B]">' . esc_html__( 'Install Contact Form 7 or use the built-in form.', 'viet-coffee' ) . '</p>';
                echo '<a href="' . esc_url( admin_url( 'customize.php' ) ) . '" class="text-[#6B4423] underline font-semibold">' . esc_html__( 'Or edit contact information in the Customizer', 'viet-coffee' ) . '</a>';
                echo '</div>';
            }
            ?>

            <div class="mt-10 text-sm text-center text-[#64748B] space-y-1">
                <?php 
                $email = get_theme_mod( 'contact_email', 'hello@vietcoffee.com' );
                $phone = get_theme_mod( 'contact_phone', '' );
                $addr  = get_theme_mod( 'contact_address', 'Buon Ma Thuot, Vietnam' );
                ?>
                <?php if ( $email ) : ?><div><strong>Email:</strong> <a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-[#6B4423]"><?php echo esc_html( $email ); ?></a></div><?php endif; ?>
                <?php if ( $phone ) : ?><div><strong>Phone:</strong> <?php echo esc_html( $phone ); ?></div><?php endif; ?>
                <?php if ( $addr ) : ?><div><strong>Address:</strong> <?php echo esc_html( $addr ); ?></div><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

</main>
<?php get_footer(); ?>
