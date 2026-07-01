<?php
/**
 * Viet Coffee Theme functions and definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Theme setup
function viet_coffee_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'viet-coffee' ),
    ) );
    
    // Add custom image sizes if needed
    add_image_size( 'coffee-featured', 600, 600, true );
}
add_action( 'after_setup_theme', 'viet_coffee_setup' );

// Enqueue styles and scripts
function viet_coffee_scripts() {
    wp_enqueue_style( 'viet-coffee-style', get_stylesheet_uri(), array(), '1.0' );
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css' );
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap', array(), null );
    
    // Tailwind via CDN for demo - better to compile in production
    wp_enqueue_script( 'tailwind', 'https://cdn.tailwindcss.com', array(), null, true );
    
    wp_enqueue_script( 'viet-coffee-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0', true );
    
    // Localize script
    wp_localize_script( 'viet-coffee-script', 'vietCoffee', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'viet_coffee_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'viet_coffee_scripts' );

// WooCommerce customizations
function viet_coffee_woocommerce_setup() {
    // Remove default WooCommerce wrappers if using custom
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
}
add_action( 'init', 'viet_coffee_woocommerce_setup' );

// Basic admin settings page
function viet_coffee_add_admin_menu() {
    add_menu_page(
        'Viet Coffee Settings',
        'Viet Coffee',
        'manage_options',
        'viet-coffee-settings',
        'viet_coffee_settings_page',
        'dashicons-coffee',
        60
    );
}
add_action( 'admin_menu', 'viet_coffee_add_admin_menu' );

function viet_coffee_settings_page() {
    ?>
    <div class="wrap">
        <h1>Viet Coffee Theme Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'viet_coffee_settings' );
            do_settings_sections( 'viet-coffee-settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function viet_coffee_settings_init() {
    register_setting( 'viet_coffee_settings', 'viet_coffee_options' );
    
    add_settings_section(
        'viet_coffee_main_section',
        'Main Settings',
        null,
        'viet-coffee-settings'
    );
    
    add_settings_field(
        'featured_product_id',
        'Featured Product ID',
        'viet_coffee_featured_product_callback',
        'viet-coffee-settings',
        'viet_coffee_main_section'
    );
    
    // Add more fields as needed
}
add_action( 'admin_init', 'viet_coffee_settings_init' );

function viet_coffee_featured_product_callback() {
    $options = get_option( 'viet_coffee_options' );
    $value = isset( $options['featured_product_id'] ) ? $options['featured_product_id'] : '';
    echo '<input type="text" id="featured_product_id" name="viet_coffee_options[featured_product_id]" value="' . esc_attr( $value ) . '" />';
}

// ============================================================
// AJAX: Add to Cart
// ============================================================
function viet_coffee_ajax_add_to_cart() {
    check_ajax_referer( 'viet_coffee_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product.', 'viet-coffee' ) ) );
    }

    $added = WC()->cart->add_to_cart( $product_id );

    if ( $added ) {
        WC()->cart->calculate_totals();
        wp_send_json_success( array(
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Could not add to cart.', 'viet-coffee' ) ) );
    }
}
add_action( 'wp_ajax_add_to_cart',        'viet_coffee_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_add_to_cart', 'viet_coffee_ajax_add_to_cart' );

// ============================================================
// AJAX: Buy Now (empty cart → add product → return checkout URL)
// ============================================================
function viet_coffee_ajax_buy_now() {
    check_ajax_referer( 'viet_coffee_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $quantity   = isset( $_POST['quantity'] )   ? absint( $_POST['quantity'] )   : 1;

    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product.', 'viet-coffee' ) ) );
    }

    WC()->cart->empty_cart();
    $added = WC()->cart->add_to_cart( $product_id, max( 1, $quantity ) );

    if ( $added ) {
        wp_send_json_success( array( 'checkout_url' => wc_get_checkout_url() ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Could not process Buy Now.', 'viet-coffee' ) ) );
    }
}
add_action( 'wp_ajax_buy_now',        'viet_coffee_ajax_buy_now' );
add_action( 'wp_ajax_nopriv_buy_now', 'viet_coffee_ajax_buy_now' );

// ============================================================
// Buy Now Modal HTML (rendered in wp_footer)
// ============================================================
function viet_coffee_buy_now_modal() {
    ?>
    <div id="buy-now-modal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true" aria-labelledby="buy-now-title">
        <div id="buy-now-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 id="buy-now-title" class="text-2xl font-bold text-[#4A2C1A]">
                        <?php esc_html_e( 'Xác nhận đặt hàng', 'viet-coffee' ); ?>
                    </h2>
                    <button id="buy-now-close" aria-label="<?php esc_attr_e( 'Close', 'viet-coffee' ); ?>"
                            class="text-gray-400 hover:text-gray-700 text-3xl leading-none">&times;</button>
                </div>

                <p id="buy-now-product-name" class="text-lg font-semibold text-gray-800 mb-1"></p>
                <p id="buy-now-product-price" class="text-amber-700 font-bold text-xl mb-6"></p>

                <div class="flex items-center gap-4 mb-6">
                    <span class="text-gray-600 font-medium"><?php esc_html_e( 'Số lượng:', 'viet-coffee' ); ?></span>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden select-none">
                        <button id="buy-now-qty-minus"
                                class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-xl font-bold text-gray-700"
                                aria-label="<?php esc_attr_e( 'Decrease quantity', 'viet-coffee' ); ?>">−</button>
                        <input id="buy-now-qty" type="number" value="1" min="1" max="99"
                               class="w-14 text-center border-none outline-none py-2 font-semibold text-gray-800" />
                        <button id="buy-now-qty-plus"
                                class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-xl font-bold text-gray-700"
                                aria-label="<?php esc_attr_e( 'Increase quantity', 'viet-coffee' ); ?>">+</button>
                    </div>
                </div>

                <button id="buy-now-confirm"
                        class="w-full bg-[#4A2C1A] hover:bg-[#3a2014] text-white py-4 rounded-2xl font-semibold text-lg transition-colors disabled:opacity-60">
                    <?php esc_html_e( 'Đặt hàng ngay', 'viet-coffee' ); ?>
                </button>
                <p id="buy-now-error" class="text-red-500 text-sm text-center mt-3 hidden"></p>
                <p id="buy-now-loading" class="text-center text-gray-500 text-sm mt-3 hidden">
                    <?php esc_html_e( 'Đang xử lý…', 'viet-coffee' ); ?>
                </p>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'viet_coffee_buy_now_modal' );
?>