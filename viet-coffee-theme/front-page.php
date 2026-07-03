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
<section class="hero-bg min-h-screen"></section>

<!-- TRUST / GUARANTEE SECTION -->
<section class="py-12 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php 
            if ( function_exists( 'viet_coffee_get_trust_items' ) ) {
                foreach ( viet_coffee_get_trust_items() as $item ) {
                    echo '<div class="flex flex-col items-center text-center">';
                    echo '<div class="text-3xl mb-3" style="color: #D4A574;"><i class="fa-solid ' . esc_attr( $item['icon'] ) . '"></i></div>';
                    echo '<span class="font-semibold text-sm text-[#3D2817]">' . esc_html( $item['text'] ) . '</span>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- SIGNATURE SELECTION -->
<?php if ( $show_featured ) : ?>
<section class="py-20 bg-white" id="featured">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <span class="inline-block px-6 py-2 bg-[#FBF8F3] text-[#D4A574] rounded-full text-sm font-medium tracking-wide" style="background-color: #FBF8F3; color: #D4A574;">✨ FEATURED</span>
        <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#3D2817] mt-6 mb-6" style="color: #3D2817;">Bộ sưu tập tinh chọn</h2>
        <?php
        $sig_opts = get_option( 'viet_coffee_options', array() );
        $sig_text = isset( $sig_opts['signature_text'] ) ? $sig_opts['signature_text'] : '';
        if ( $sig_text ) {
            echo '<p class="text-lg text-[#64748B] leading-relaxed">' . nl2br( esc_html( $sig_text ) ) . '</p>';
        } else {
            echo '<p class="text-[#64748B] italic">' . esc_html__( 'Các sản phẩm cà phê hàng đầu được chọn cẩn thận từ các vùng cao nguyên Tây Nguyên. Roasted tươi mỗi ngày.', 'viet-coffee' ) . '</p>';
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
                <h2 class="text-4xl heading-font font-bold text-[#3D2817]" style="color: #3D2817;">Bộ sưu tập cà phê</h2>
                <p class="text-[#64748B] text-lg mt-2">Những hạt cà phê chất lượng cao từ Việt Nam</p>
            </div>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="mt-6 md:mt-0 inline-flex items-center gap-2 text-sm font-semibold text-[#6B4423] hover:text-[#D4A574] transition-colors">
                <?php esc_html_e( 'Xem tất cả →', 'viet-coffee' ); ?>
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
                echo '<div class="text-center py-12"><p class="text-[#64748B] text-lg">' . esc_html__( 'Chưa có sản phẩm. Hãy thêm sản phẩm trong WooCommerce.', 'viet-coffee' ) . '</p></div>';
            }
        } else {
            echo '<p class="text-center text-[#64748B]">' . esc_html__( 'WooCommerce là bắt buộc cho shop.', 'viet-coffee' ) . '</p>';
        }
        ?>
    </div>
</section>
<?php endif; ?>

<!-- CONTACT -->
<?php if ( $show_contact ) : ?>
<section id="contact" class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-10">
            <span class="inline-block px-6 py-2 bg-[#FBF8F3] text-[#D4A574] rounded-full text-sm font-medium tracking-wide" style="background-color: #FBF8F3; color: #D4A574;">✉️ CONTACT</span>
            <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#3D2817] mt-6" style="color: #3D2817;">Liên hệ với chúng tôi</h2>
            <p class="text-[#64748B] mt-3">Có câu hỏi? Chúng tôi rất vui được nghe từ bạn.</p>
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
                echo '<p class="mb-3 text-[#64748B]">' . esc_html__( 'Cài đặt Contact Form 7 hoặc sử dụng biểu mẫu tích hợp.', 'viet-coffee' ) . '</p>';
                echo '<a href="' . esc_url( admin_url( 'customize.php' ) ) . '" class="text-[#6B4423] underline font-semibold">' . esc_html__( 'Hoặc chỉnh sửa thông tin liên hệ trong Customizer', 'viet-coffee' ) . '</a>';
                echo '</div>';
            }
            ?>

            <div class="mt-10 text-sm text-center text-[#64748B] space-y-1">
                <?php 
                $email = get_theme_mod( 'contact_email', 'hello@vietcoffee.com' );
                $phone = get_theme_mod( 'contact_phone', '' );
                $addr  = get_theme_mod( 'contact_address', 'Buôn Ma Thuột, Việt Nam' );
                ?>
                <?php if ( $email ) : ?><div><strong>Email:</strong> <a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-[#6B4423]"><?php echo esc_html( $email ); ?></a></div><?php endif; ?>
                <?php if ( $phone ) : ?><div><strong>Điện thoại:</strong> <?php echo esc_html( $phone ); ?></div><?php endif; ?>
                <?php if ( $addr ) : ?><div><strong>Địa chỉ:</strong> <?php echo esc_html( $addr ); ?></div><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
