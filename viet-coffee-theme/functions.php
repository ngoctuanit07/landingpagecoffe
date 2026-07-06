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

    // Professional WordPress features
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'editor-styles' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'viet-coffee' ),
    ) );

    // Add custom image sizes
    add_image_size( 'coffee-featured', 600, 600, true );
    add_image_size( 'coffee-card', 400, 320, true );
}
add_action( 'after_setup_theme', 'viet_coffee_setup' );

/**
 * Professional footer widget areas
 */
function viet_coffee_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Footer Column 1', 'viet-coffee' ),
        'id'            => 'footer-1',
        'description'   => __( 'Left column in footer.', 'viet-coffee' ),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="font-semibold mb-4 text-white">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Column 2', 'viet-coffee' ),
        'id'            => 'footer-2',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="font-semibold mb-4 text-white">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Column 3', 'viet-coffee' ),
        'id'            => 'footer-3',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="font-semibold mb-4 text-white">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'viet_coffee_widgets_init' );

// Load modular includes for better organization & pro features
$inc_dir = get_template_directory() . '/inc/';
if ( file_exists( $inc_dir . 'customizer.php' ) ) {
    require_once $inc_dir . 'customizer.php';
}
if ( file_exists( $inc_dir . 'admin.php' ) ) {
    require_once $inc_dir . 'admin.php';
}
if ( file_exists( $inc_dir . 'woocommerce.php' ) ) {
    require_once $inc_dir . 'woocommerce.php';
}
if ( file_exists( $inc_dir . 'helpers.php' ) ) {
    require_once $inc_dir . 'helpers.php';
}

// Enqueue styles and scripts
function viet_coffee_scripts() {
    $style_path = get_stylesheet_directory() . '/style.css';
    wp_enqueue_style( 'viet-coffee-style', get_stylesheet_uri(), array(), file_exists($style_path) ? filemtime($style_path) : '1.1' );
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css' );
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap', array(), null );
    
    // Tailwind via CDN for demo - better to compile in production
    wp_enqueue_script( 'tailwind', 'https://cdn.tailwindcss.com', array(), null, false );
    
    // Use filemtime for automatic cache busting (important after JS fixes)
    $main_js_path = get_template_directory() . '/js/main.js';
    $main_js_ver  = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : '1.1';
    wp_enqueue_script( 'viet-coffee-script', get_template_directory_uri() . '/js/main.js', array('jquery'), $main_js_ver, true );

    // Ensure WooCommerce cart AJAX fragments are available for mini-cart interactivity
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'wc-cart-fragments' );
        // Native WooCommerce AJAX add to cart (key for following WC flow)
        wp_enqueue_script( 'wc-add-to-cart' );

        // Note: We no longer force-load full wc-checkout in modals.
        // Checkout is handled on the native WooCommerce checkout page.
        // Only load if you have custom needs on other pages.
        // wp_enqueue_script( 'wc-checkout' );

        // Base WooCommerce styles (still useful for notices, buttons on shop/cart)
        wp_enqueue_style( 'woocommerce-general' );
        wp_enqueue_style( 'woocommerce-layout' );
        wp_enqueue_style( 'woocommerce-smallscreen' );
    }
    
    // Localize script
    wp_localize_script( 'viet-coffee-script', 'vietCoffee', array(
        'ajax_url'     => admin_url( 'admin-ajax.php' ),
        'nonce'        => wp_create_nonce( 'viet_coffee_nonce' ),
        'checkout_url' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout' ),
        'cart_url'     => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart' ),
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

// Old admin page removed — now handled by inc/admin.php (much nicer UX + links to Customizer).
// The demo import functions below remain active.

// ============================================================
// AJAX: Buy Now (empty cart → add product → return checkout URL)
// Direct-to-checkout flow (still useful for "Buy Now" buttons)
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

// Simple native contact form handler (AJAX)
function viet_coffee_ajax_contact() {
    check_ajax_referer( 'viet_coffee_nonce', 'nonce' );

    $name    = isset( $_POST['name'] )    ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $message = isset( $_POST['message'] ) ? wp_kses_post( wp_unslash( $_POST['message'] ) ) : '';

    if ( ! $name || ! $email || ! $message ) {
        wp_send_json_error( __( 'Please fill in all fields.', 'viet-coffee' ) );
    }

    $to      = get_theme_mod( 'contact_email', get_option( 'admin_email' ) );
    $subject = sprintf( __( 'New contact from %s via Viet Coffee site', 'viet-coffee' ), $name );
    $body    = "Name: $name\nEmail: $email\n\nMessage:\n$message\n\n--\nSent from " . home_url();
    $headers = array( 'Reply-To: ' . $email );

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( __( 'Thank you! Your message has been sent.', 'viet-coffee' ) );
    } else {
        wp_send_json_error( __( 'Sorry, we could not send the message right now.', 'viet-coffee' ) );
    }
}
add_action( 'wp_ajax_viet_coffee_contact',        'viet_coffee_ajax_contact' );
add_action( 'wp_ajax_nopriv_viet_coffee_contact', 'viet_coffee_ajax_contact' );

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

// ============================================================
// Simple AJAX cart count (kept for header sync / manual refresh)
// Native WooCommerce fragments also update #cart-count via filter
// ============================================================
function viet_coffee_ajax_get_cart_count() {
    // Nonce optional for read-only count but kept for consistency
    if ( isset( $_REQUEST['nonce'] ) ) {
        check_ajax_referer( 'viet_coffee_nonce', 'nonce' );
    }
    wp_send_json_success( array(
        'count' => class_exists('WooCommerce') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
    ) );
}
add_action( 'wp_ajax_get_cart_count', 'viet_coffee_ajax_get_cart_count' );
add_action( 'wp_ajax_nopriv_get_cart_count', 'viet_coffee_ajax_get_cart_count' );

// ============================================================
// SEARCH MODAL (simple, invoked by header button)
// ============================================================
function viet_coffee_search_modal() {
    ?>
    <div id="search-modal" class="fixed inset-0 z-[9999] hidden" role="dialog">
        <div id="search-overlay" class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 flex items-start justify-center pt-24 p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-4">
                <form id="site-search-form" action="/" method="get" class="flex gap-2">
                    <input type="hidden" name="post_type" value="product">
                    <input type="text" name="s" id="site-search-input"
                           class="flex-1 border border-gray-300 focus:border-[#4A2C1A] outline-none rounded-2xl px-5 py-3 text-lg"
                           placeholder="Search coffees..." autocomplete="off">
                    <button type="submit"
                            class="px-6 rounded-2xl bg-[#4A2C1A] text-white font-semibold">Search</button>
                </form>
                <div class="text-right mt-2">
                    <button id="search-close" class="text-xs text-gray-500 hover:text-gray-700">ESC to close</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'viet_coffee_search_modal' );

// ============================================================
// DEMO DATA IMPORT SYSTEM
// ============================================================

/**
 * Helper: Download remote image and attach to a post.
 */
function viet_coffee_sideload_image( $url, $post_id = 0, $desc = '' ) {
    if ( ! function_exists( 'media_handle_sideload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $tmp = download_url( $url );
    if ( is_wp_error( $tmp ) ) {
        return 0;
    }

    $file_array = array(
        'name'     => wp_basename( $url ) . '.jpg',
        'tmp_name' => $tmp,
    );

    $attachment_id = media_handle_sideload( $file_array, $post_id, $desc );

    if ( is_wp_error( $attachment_id ) ) {
        @unlink( $tmp );
        return 0;
    }

    return $attachment_id;
}

/**
 * Create / update product categories.
 */
function viet_coffee_ensure_product_categories() {
    $cats = array(
        'single-origin' => 'Single Origin',
        'blends'        => 'Blends',
        'premium'       => 'Premium',
    );

    $ids = array();
    foreach ( $cats as $slug => $name ) {
        $term = get_term_by( 'slug', $slug, 'product_cat' );
        if ( ! $term ) {
            $res = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
            if ( ! is_wp_error( $res ) ) {
                $ids[ $slug ] = (int) $res['term_id'];
            }
        } else {
            $ids[ $slug ] = (int) $term->term_id;
        }
    }
    return $ids;
}

/**
 * Main import logic. Idempotent (skips products with existing SKU).
 */
function viet_coffee_do_import_demo_data() {
    if ( ! class_exists( 'WC_Product_Simple' ) ) {
        return new WP_Error( 'no_woo', 'WooCommerce is required to import products.' );
    }

    $created_products = 0;
    $cat_ids = viet_coffee_ensure_product_categories();

    // Sample products
    $products_data = array(
        array(
            'name'         => 'Robusta Buôn Ma Thuột 250g',
            'sku'          => 'vcf-robusta-bmt-250',
            'price'        => '12.50',
            'short_desc'   => 'Hương vị đậm đà, đắng nhẹ đặc trưng của robusta Việt Nam. Ghi chú socola đen và hạt.',
            'description'  => 'Cà phê Robusta từ vùng đất đỏ bazan Buôn Ma Thuột nổi tiếng. Hạt được chọn lọc kỹ càng, rang vừa để giữ nguyên hương vị mạnh mẽ, phù hợp pha phin truyền thống hoặc espresso.',
            'image'        => 'https://picsum.photos/id/201/800/800',
            'cats'         => array( 'single-origin' ),
        ),
        array(
            'name'         => 'Arabica Đà Lạt 250g',
            'sku'          => 'vcf-arabica-dl-250',
            'price'        => '15.90',
            'short_desc'   => 'Hương hoa nhài, vị chua thanh và ngọt hậu. Arabica thượng hạng từ cao nguyên Lâm Đồng.',
            'description'  => 'Được trồng ở độ cao 1500m tại Đà Lạt. Hạt Arabica có hương vị tinh tế, axit nhẹ nhàng, lý tưởng cho drip, pour-over hoặc cold brew.',
            'image'        => 'https://picsum.photos/id/29/800/800',
            'cats'         => array( 'single-origin' ),
        ),
        array(
            'name'         => 'Cà Phê Chồn Weasel 100g',
            'sku'          => 'vcf-weasel-100',
            'price'        => '45.00',
            'short_desc'   => 'Loại cà phê hiếm và đắt nhất thế giới. Hương vị mượt mà, ít đắng, hậu vị kéo dài.',
            'description'  => 'Cà phê được chế biến qua hệ tiêu hóa của chồn hương. Quy trình tự nhiên tạo ra hương vị độc đáo, mượt mà không thể nhầm lẫn. Số lượng giới hạn.',
            'image'        => 'https://picsum.photos/id/312/800/800',
            'cats'         => array( 'premium' ),
        ),
        array(
            'name'         => 'Vietnam Espresso Blend 500g',
            'sku'          => 'vcf-espresso-500',
            'price'        => '18.90',
            'short_desc'   => 'Sự kết hợp hoàn hảo giữa Robusta và Arabica. Tạo lớp crema dày và vị cân bằng.',
            'description'  => 'Blend chuyên dụng cho máy espresso hoặc pha phin mạnh. Hương socola, hạt và chút trái cây. Tỷ lệ 60% Robusta + 40% Arabica.',
            'image'        => 'https://picsum.photos/id/160/800/800',
            'cats'         => array( 'blends' ),
        ),
        array(
            'name'         => 'Cao Nguyên Medium Roast 250g',
            'sku'          => 'vcf-highland-250',
            'price'        => '11.00',
            'short_desc'   => 'Hương caramel, hạt rang vừa phải. Phù hợp uống hàng ngày cho mọi người.',
            'description'  => 'Cà phê rang vừa từ vùng cao nguyên, mang đến sự cân bằng giữa đắng và chua. Dễ uống, phù hợp cả hot brew và cold brew.',
            'image'        => 'https://picsum.photos/id/251/800/800',
            'cats'         => array( 'blends' ),
        ),
        array(
            'name'         => 'Bộ Sampler 4 Vị Cà Phê',
            'sku'          => 'vcf-sampler-4',
            'price'        => '16.50',
            'short_desc'   => 'Thử 4 loại cà phê đặc sản. Bao gồm Robusta, Arabica, Blend và một loại đặc biệt.',
            'description'  => 'Bộ sưu tập mini 4x50g. Hoàn hảo để khám phá các hương vị khác nhau của cà phê Việt Nam. Kèm hướng dẫn pha nhanh.',
            'image'        => 'https://picsum.photos/id/133/800/800',
            'cats'         => array( 'premium' ),
        ),
    );

    $first_product_id = 0;

    foreach ( $products_data as $data ) {
        $existing_id = wc_get_product_id_by_sku( $data['sku'] );
        if ( $existing_id ) {
            if ( ! $first_product_id ) {
                $first_product_id = $existing_id;
            }
            continue;
        }

        $product = new WC_Product_Simple();
        $product->set_name( $data['name'] );
        $product->set_sku( $data['sku'] );
        $product->set_regular_price( $data['price'] );
        $product->set_short_description( $data['short_desc'] );
        $product->set_description( $data['description'] );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        $product->set_stock_status( 'instock' );
        $product->set_manage_stock( false );

        // Categories
        $term_ids = array();
        foreach ( $data['cats'] as $slug ) {
            if ( isset( $cat_ids[ $slug ] ) ) {
                $term_ids[] = $cat_ids[ $slug ];
            }
        }
        if ( ! empty( $term_ids ) ) {
            $product->set_category_ids( $term_ids );
        }

        $product_id = $product->save();

        if ( $product_id ) {
            // Attach image
            $img_id = viet_coffee_sideload_image( $data['image'], $product_id, $data['name'] );
            if ( $img_id ) {
                $product->set_image_id( $img_id );
                $product->save();
            }

            $created_products++;

            if ( ! $first_product_id ) {
                $first_product_id = $product_id;
            }
        }
    }

    // Create / update Our Story page
    $story_page = get_page_by_path( 'our-story' );
    $story_content = viet_coffee_get_story_content();

    if ( $story_page ) {
        wp_update_post( array(
            'ID'           => $story_page->ID,
            'post_content' => $story_content,
            'post_status'  => 'publish',
        ) );
        $story_id = $story_page->ID;
    } else {
        $story_id = wp_insert_post( array(
            'post_title'   => 'Our Story',
            'post_name'    => 'our-story',
            'post_content' => $story_content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ) );
    }

    // Set featured image for story page if none
    if ( $story_id && ! get_post_thumbnail_id( $story_id ) ) {
        $story_img = viet_coffee_sideload_image( 'https://picsum.photos/id/1016/1200/800', $story_id, 'Our Story' );
        if ( $story_img ) {
            set_post_thumbnail( $story_id, $story_img );
        }
    }

    // Create / replace Primary Menu
    viet_coffee_setup_primary_menu( $story_id );

    // Set featured product
    $opts = get_option( 'viet_coffee_options', array() );
    if ( $first_product_id ) {
        $opts['featured_product_id'] = $first_product_id;
        update_option( 'viet_coffee_options', $opts );
    }

    // Hero settings
    set_theme_mod( 'hero_title', 'AUTHENTIC VIETNAMESE COFFEE' );
    set_theme_mod( 'hero_subtitle', 'Single-origin beans from Vietnam\'s finest highlands. Freshly roasted with passion and delivered worldwide.' );
    set_theme_mod( 'hero_background', 'https://picsum.photos/id/1016/2000/1200' );

    update_option( 'viet_coffee_demo_imported', true );

    $msg = sprintf(
        '✅ Demo import complete. Created %d new products. Story page & menu ready.',
        $created_products
    );

    return $msg;
}

/**
 * Beautiful story content (Vietnamese + English flavor).
 */
function viet_coffee_get_story_content() {
    return '
<div class="max-w-4xl mx-auto px-2">
    <div class="mb-10">
        <span class="inline-block px-5 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium tracking-wider">OUR HERITAGE</span>
        <h2 class="text-4xl md:text-5xl heading-font font-bold text-[#4A2C1A] mt-4 mb-6">Từ Cao Nguyên Việt Nam Đến Tách Cà Phê Của Bạn</h2>
    </div>

    <div class="prose prose-lg max-w-none text-gray-700">
        <p class="text-xl">Chúng tôi là những người con của đất Việt, đam mê mang tinh hoa cà phê Việt Nam đến với thế giới.</p>

        <p>Chúng tôi làm việc trực tiếp với những nông dân tại <strong>Buôn Ma Thuột</strong>, <strong>Đà Lạt</strong> và các vùng cao nguyên trứ danh khác. Mỗi hạt cà phê đều được chọn lọc kỹ lưỡng, thu hoạch đúng mùa và chế biến theo phương pháp truyền thống kết hợp công nghệ hiện đại.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-10 not-prose">
            <div class="p-6 bg-[#f8f1e9] rounded-2xl">
                <div class="text-3xl mb-3">🌿</div>
                <h4 class="font-semibold text-lg mb-1 text-[#4A2C1A]">Direct Trade</h4>
                <p class="text-sm">Hợp tác công bằng, giá cao hơn cho nông dân và chất lượng vượt trội cho bạn.</p>
            </div>
            <div class="p-6 bg-[#f8f1e9] rounded-2xl">
                <div class="text-3xl mb-3">☕</div>
                <h4 class="font-semibold text-lg mb-1 text-[#4A2C1A]">Small Batch Roast</h4>
                <p class="text-sm">Rang thủ công theo mẻ nhỏ mỗi ngày để giữ trọn vẹn hương vị tươi mới.</p>
            </div>
            <div class="p-6 bg-[#f8f1e9] rounded-2xl">
                <div class="text-3xl mb-3">🌍</div>
                <h4 class="font-semibold text-lg mb-1 text-[#4A2C1A]">Global Delivery</h4>
                <p class="text-sm">Đóng gói chuyên nghiệp và giao hàng nhanh chóng đến mọi nơi trên thế giới.</p>
            </div>
        </div>

        <p>Từ những hạt Robusta mạnh mẽ của Buôn Ma Thuột đến Arabica tinh tế Đà Lạt, và cả Cà Phê Chồn huyền thoại – mỗi sản phẩm đều kể một câu chuyện về vùng đất, con người và niềm tự hào Việt Nam.</p>

        <p class="text-lg font-medium text-[#4A2C1A]">Uống một tách – cảm nhận một đất nước.</p>
    </div>
</div>';
}

/**
 * Create primary navigation menu with links.
 */
function viet_coffee_setup_primary_menu( $story_page_id = 0 ) {
    $menu_name = 'Viet Coffee Primary';
    $menu = wp_get_nav_menu_object( $menu_name );

    if ( $menu ) {
        wp_delete_nav_menu( $menu->term_id );
    }

    $menu_id = wp_create_nav_menu( $menu_name );

    if ( is_wp_error( $menu_id ) ) {
        return;
    }

    // Products (scroll to collection on front page)
    wp_update_nav_menu_item( $menu_id, 0, array(
        'menu-item-title'  => 'Shop',
        'menu-item-url'    => home_url( '/#products' ),
        'menu-item-status' => 'publish',
        'menu-item-type'   => 'custom',
    ) );

    // Our Story (scroll to inline story section)
    wp_update_nav_menu_item( $menu_id, 0, array(
        'menu-item-title'  => 'Our Story',
        'menu-item-url'    => home_url( '/#stories' ),
        'menu-item-status' => 'publish',
        'menu-item-type'   => 'custom',
    ) );

    // Contact (anchor)
    wp_update_nav_menu_item( $menu_id, 0, array(
        'menu-item-title'  => 'Contact',
        'menu-item-url'    => home_url( '/#contact' ),
        'menu-item-status' => 'publish',
        'menu-item-type'   => 'custom',
    ) );

    // Assign to primary location
    $locations = get_theme_mod( 'nav_menu_locations', array() );
    $locations['primary'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * AJAX: Run the import.
 */
function viet_coffee_ajax_import_demo() {
    check_ajax_referer( 'viet_coffee_demo_import', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Bạn không có quyền thực hiện thao tác này.' );
    }

    $result = viet_coffee_do_import_demo_data();

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }

    wp_send_json_success( $result );
}
add_action( 'wp_ajax_viet_coffee_import_demo', 'viet_coffee_ajax_import_demo' );

/**
 * AJAX: Reset demo data (products by SKU prefix, story page, menu).
 */
function viet_coffee_ajax_reset_demo() {
    check_ajax_referer( 'viet_coffee_demo_import', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $deleted = 0;

    // Delete products with our SKU prefix
    $skus = array( 'vcf-robusta-bmt-250', 'vcf-arabica-dl-250', 'vcf-weasel-100', 'vcf-espresso-500', 'vcf-highland-250', 'vcf-sampler-4' );
    foreach ( $skus as $sku ) {
        $pid = wc_get_product_id_by_sku( $sku );
        if ( $pid ) {
            wp_delete_post( $pid, true ); // force delete
            $deleted++;
        }
    }

    // Delete story page
    $story = get_page_by_path( 'our-story' );
    if ( $story ) {
        wp_delete_post( $story->ID, true );
    }

    // Remove primary menu we created
    $menu = wp_get_nav_menu_object( 'Viet Coffee Primary' );
    if ( $menu ) {
        wp_delete_nav_menu( $menu->term_id );
    }

    delete_option( 'viet_coffee_demo_imported' );

    wp_send_json_success( 'Demo data removed (' . $deleted . ' products). Refresh to see changes.' );
}
add_action( 'wp_ajax_viet_coffee_reset_demo', 'viet_coffee_ajax_reset_demo' );
?>