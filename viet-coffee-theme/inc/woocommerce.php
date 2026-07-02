<?php
/**
 * Additional WooCommerce enhancements for professional experience
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add body class for modal mode
add_filter( 'body_class', function( $classes ) {
    if ( is_front_page() ) {
        $classes[] = 'viet-coffee-landing';
    }
    return $classes;
});

// Ensure cart fragments always update header count
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    ob_start();
    ?>
    <span id="cart-count" class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
        <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
    <?php
    $fragments['#cart-count'] = ob_get_clean();
    return $fragments;
});

// Allow shortcode for products on any page (useful for landing)
add_shortcode( 'viet_coffee_products', 'viet_coffee_products_shortcode' );
function viet_coffee_products_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'limit'    => 6,
        'category' => '',
    ), $atts );

    ob_start();

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval( $atts['limit'] ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $atts['category'] ),
            ),
        );
    }

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">';
        while ( $query->have_posts() ) {
            $query->the_post();
            global $product;
            $product = wc_get_product( get_the_ID() );
            wc_get_template_part( 'template-parts/content', 'product' );
        }
        echo '</div>';
        wp_reset_postdata();
    }
    return ob_get_clean();
}

// Quick view modal support (lightweight AJAX endpoint)
add_action( 'wp_ajax_viet_coffee_quick_view', 'viet_coffee_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_viet_coffee_quick_view', 'viet_coffee_ajax_quick_view' );

function viet_coffee_ajax_quick_view() {
    check_ajax_referer( 'viet_coffee_nonce', 'nonce' );
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    if ( ! $product_id || ! class_exists( 'WooCommerce' ) ) {
        wp_send_json_error();
    }

    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        wp_send_json_error();
    }

    ob_start();
    ?>
    <div class="flex flex-col md:flex-row gap-8">
        <div class="md:w-5/12">
            <?php echo $product->get_image( 'large' ); ?>
        </div>
        <div class="md:w-7/12">
            <h2 class="text-3xl font-bold mb-2"><?php echo esc_html( $product->get_name() ); ?></h2>
            <div class="text-2xl font-semibold text-amber-700 mb-4"><?php echo $product->get_price_html(); ?></div>
            <div class="prose prose-sm max-w-none mb-6 text-gray-600">
                <?php echo wp_kses_post( $product->get_short_description() ); ?>
            </div>

            <?php if ( $product->is_in_stock() ) : ?>
                <div class="flex gap-3">
                    <button data-add-to-cart="<?php echo esc_attr( $product->get_id() ); ?>"
                            class="flex-1 bg-[#4A2C1A] hover:bg-black text-white py-3.5 rounded-2xl font-semibold">
                        <?php esc_html_e( 'Add to Cart', 'viet-coffee' ); ?>
                    </button>
                    <button onclick="buyNowQuick(<?php echo esc_attr( $product->get_id() ); ?>, '<?php echo esc_js( $product->get_name() ); ?>', '<?php echo esc_js( strip_tags( $product->get_price_html() ) ); ?>');"
                            class="flex-1 border border-[#4A2C1A] text-[#4A2C1A] py-3.5 rounded-2xl font-semibold hover:bg-[#4A2C1A] hover:text-white">
                        <?php esc_html_e( 'Buy Now', 'viet-coffee' ); ?>
                    </button>
                </div>
            <?php endif; ?>

            <p class="mt-4 text-xs text-gray-500">
                <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="underline">
                    <?php esc_html_e( 'View full product details', 'viet-coffee' ); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
    wp_send_json_success( ob_get_clean() );
}
