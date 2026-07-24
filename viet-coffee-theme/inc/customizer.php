<?php
/**
 * Viet Coffee Theme Customizer
 * Professional, easy-to-use controls via Appearance > Customize
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function viet_coffee_customize_register( $wp_customize ) {

    // =========================================================
    // HERO SECTION
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_hero', array(
        'title'       => __( 'Hero Banner', 'viet-coffee' ),
        'priority'    => 30,
        'description' => __( 'Edit the main homepage hero section.', 'viet-coffee' ),
    ) );

    $wp_customize->add_setting( 'hero_title', array(
        'default'           => 'AUTHENTIC VIETNAMESE COFFEE',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'hero_title', array(
        'label'   => __( 'Hero Title', 'viet-coffee' ),
        'section' => 'viet_coffee_hero',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'hero_subtitle', array(
        'default'           => 'Single-origin beans from Vietnam\'s finest highlands. Freshly roasted with passion and delivered worldwide.',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'hero_subtitle', array(
        'label'   => __( 'Hero Subtitle', 'viet-coffee' ),
        'section' => 'viet_coffee_hero',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'hero_button_text', array(
        'default'           => 'SHOP OUR COLLECTION',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'hero_button_text', array(
        'label'   => __( 'Button Text', 'viet-coffee' ),
        'section' => 'viet_coffee_hero',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'hero_button_link', array(
        'default'           => '#products',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'hero_button_link', array(
        'label'   => __( 'Button Link', 'viet-coffee' ),
        'section' => 'viet_coffee_hero',
        'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'hero_background', array(
        'default'           => 'https://picsum.photos/id/1016/2000/1200',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_background', array(
        'label'    => __( 'Background Image', 'viet-coffee' ),
        'section'  => 'viet_coffee_hero',
        'settings' => 'hero_background',
    ) ) );

    $wp_customize->add_setting( 'hero_overlay', array(
        'default'           => 0.5,
        'sanitize_callback' => 'viet_coffee_sanitize_float',
    ) );
    $wp_customize->add_control( 'hero_overlay', array(
        'label'       => __( 'Overlay Darkness', 'viet-coffee' ),
        'section'     => 'viet_coffee_hero',
        'type'        => 'range',
        'input_attrs' => array( 'min' => 0, 'max' => 0.9, 'step' => 0.05 ),
    ) );

    // =========================================================
    // FEATURED SECTION
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_featured', array(
        'title'    => __( 'Featured Section', 'viet-coffee' ),
        'priority' => 32,
    ) );

    $wp_customize->add_setting( 'featured_badge', array(
        'default'           => '✨ FEATURED',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'featured_badge', array(
        'label'   => __( 'Badge Text', 'viet-coffee' ),
        'section' => 'viet_coffee_featured',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'featured_title', array(
        'default'           => 'Bộ sưu tập tinh chọn',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'featured_title', array(
        'label'   => __( 'Section Title', 'viet-coffee' ),
        'section' => 'viet_coffee_featured',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'featured_description', array(
        'default'           => 'Các sản phẩm cà phê hàng đầu được chọn cẩn thận từ các vùng cao nguyên Tây Nguyên. Roasted tươi mỗi ngày.',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'featured_description', array(
        'label'   => __( 'Description', 'viet-coffee' ),
        'section' => 'viet_coffee_featured',
        'type'    => 'textarea',
    ) );

    // =========================================================
    // PRODUCTS SECTION
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_products', array(
        'title'    => __( 'Products Section', 'viet-coffee' ),
        'priority' => 33,
    ) );

    $wp_customize->add_setting( 'products_title', array(
        'default'           => 'Bộ sưu tập cà phê',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'products_title', array(
        'label'   => __( 'Section Title', 'viet-coffee' ),
        'section' => 'viet_coffee_products',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'products_subtitle', array(
        'default'           => 'Những hạt cà phê chất lượng cao từ Việt Nam',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'products_subtitle', array(
        'label'   => __( 'Subtitle', 'viet-coffee' ),
        'section' => 'viet_coffee_products',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'products_view_all_text', array(
        'default'           => 'Xem tất cả →',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'products_view_all_text', array(
        'label'   => __( '"View All" Button Text', 'viet-coffee' ),
        'section' => 'viet_coffee_products',
        'type'    => 'text',
    ) );

    // =========================================================
    // HOMEPAGE SECTIONS (Toggle)
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_homepage', array(
        'title'    => __( 'Homepage Sections (Toggle)', 'viet-coffee' ),
        'priority' => 35,
    ) );

    // Toggle sections
    $sections = array(
        'show_featured' => __( 'Show Featured Product', 'viet-coffee' ),
        'show_products' => __( 'Show Products Collection', 'viet-coffee' ),
        'show_stories'  => __( 'Show Our Story', 'viet-coffee' ),
        'show_testimonials' => __( 'Show Testimonials', 'viet-coffee' ),
        'show_contact'  => __( 'Show Contact Section', 'viet-coffee' ),
    );

    foreach ( $sections as $id => $label ) {
        $wp_customize->add_setting( $id, array(
            'default'           => 1,
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control( $id, array(
            'label'   => $label,
            'section' => 'viet_coffee_homepage',
            'type'    => 'checkbox',
        ) );
    }

    $wp_customize->add_setting( 'products_per_page', array(
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'products_per_page', array(
        'label'       => __( 'Number of Products (homepage)', 'viet-coffee' ),
        'section'     => 'viet_coffee_homepage',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 3, 'max' => 12, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( 'products_category', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'products_category', array(
        'label'       => __( 'Filter by Category (slug or leave blank)', 'viet-coffee' ),
        'description' => __( 'E.g. single-origin or blends', 'viet-coffee' ),
        'section'     => 'viet_coffee_homepage',
        'type'        => 'text',
    ) );

    // =========================================================
    // COLORS & STYLE
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_style', array(
        'title'    => __( 'Colors & Style', 'viet-coffee' ),
        'priority' => 40,
    ) );

    $wp_customize->add_setting( 'primary_color', array(
        'default'           => '#4A2C1A',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
        'label'   => __( 'Primary Color', 'viet-coffee' ),
        'section' => 'viet_coffee_style',
    ) ) );

    $wp_customize->add_setting( 'accent_color', array(
        'default'           => '#B45309',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
        'label'   => __( 'Accent / Amber Color', 'viet-coffee' ),
        'section' => 'viet_coffee_style',
    ) ) );

    // =========================================================
    // STORY SECTION - REMOVED (now in Theme Settings > Story)
    // =========================================================

    // =========================================================
    // CONTACT SECTION
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_contact', array(
        'title'    => __( 'Contact & Social', 'viet-coffee' ),
        'priority' => 50,
    ) );

    $wp_customize->add_setting( 'contact_badge', array(
        'default'           => '✉️ CONTACT',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'contact_badge', array(
        'label'   => __( 'Badge Text', 'viet-coffee' ),
        'section' => 'viet_coffee_contact',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'contact_title', array(
        'default'           => 'Liên hệ với chúng tôi',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'contact_title', array(
        'label'   => __( 'Section Title', 'viet-coffee' ),
        'section' => 'viet_coffee_contact',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'contact_subtitle', array(
        'default'           => 'Có câu hỏi? Chúng tôi rất vui được nghe từ bạn.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'contact_subtitle', array(
        'label'   => __( 'Subtitle', 'viet-coffee' ),
        'section' => 'viet_coffee_contact',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'contact_email', array(
        'default'           => 'hello@vietcoffee.com',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'contact_email', array(
        'label'   => __( 'Contact Email', 'viet-coffee' ),
        'section' => 'viet_coffee_contact',
        'type'    => 'email',
    ) );

    $wp_customize->add_setting( 'contact_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'contact_phone', array(
        'label'   => __( 'Phone Number', 'viet-coffee' ),
        'section' => 'viet_coffee_contact',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'contact_address', array(
        'default'           => 'Buon Ma Thuot, Dak Lak, Vietnam',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'contact_address', array(
        'label'   => __( 'Address', 'viet-coffee' ),
        'section' => 'viet_coffee_contact',
        'type'    => 'text',
    ) );

    $socials = array(
        'social_facebook'  => __( 'Facebook URL', 'viet-coffee' ),
        'social_instagram' => __( 'Instagram URL', 'viet-coffee' ),
        'social_tiktok'    => __( 'TikTok URL (optional)', 'viet-coffee' ),
        'social_youtube'   => __( 'YouTube URL (optional)', 'viet-coffee' ),
    );

    foreach ( $socials as $id => $label ) {
        $wp_customize->add_setting( $id, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( $id, array(
            'label'   => $label,
            'section' => 'viet_coffee_contact',
            'type'    => 'url',
        ) );
    }

    // =========================================================
    // TESTIMONIALS (simple 3 testimonials)
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_testimonials', array(
        'title'       => __( 'Testimonials', 'viet-coffee' ),
        'priority'    => 55,
        'description' => __( 'Edit up to 3 customer testimonials shown on the homepage.', 'viet-coffee' ),
    ) );

    for ( $i = 1; $i <= 3; $i++ ) {
        $wp_customize->add_setting( "testimonial_{$i}_quote", array(
            'default'           => ( $i === 1 ? 'The best coffee I\'ve ever tried. Rich flavor and fast shipping!' : '' ),
            'sanitize_callback' => 'wp_kses_post',
        ) );
        $wp_customize->add_control( "testimonial_{$i}_quote", array(
            'label'   => sprintf( __( 'Testimonial %d - Quote', 'viet-coffee' ), $i ),
            'section' => 'viet_coffee_testimonials',
            'type'    => 'textarea',
        ) );

        $wp_customize->add_setting( "testimonial_{$i}_name", array(
            'default'           => ( $i === 1 ? 'Sarah Johnson' : '' ),
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "testimonial_{$i}_name", array(
            'label'   => sprintf( __( 'Testimonial %d - Name', 'viet-coffee' ), $i ),
            'section' => 'viet_coffee_testimonials',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( "testimonial_{$i}_role", array(
            'default'           => ( $i === 1 ? 'Coffee Lover, New York' : '' ),
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "testimonial_{$i}_role", array(
            'label'   => sprintf( __( 'Testimonial %d - Role / Location', 'viet-coffee' ), $i ),
            'section' => 'viet_coffee_testimonials',
            'type'    => 'text',
        ) );
    }

    // =========================================================
    // FOOTER
    // =========================================================
    $wp_customize->add_section( 'viet_coffee_footer', array(
        'title'    => __( 'Footer', 'viet-coffee' ),
        'priority' => 60,
    ) );

    $wp_customize->add_setting( 'footer_copyright', array(
        'default'           => 'All rights reserved. • International Shipping Available',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'footer_copyright', array(
        'label'   => __( 'Copyright Text', 'viet-coffee' ),
        'section' => 'viet_coffee_footer',
        'type'    => 'text',
    ) );

    // Selective refresh for live preview
    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'hero_title', array(
            'selector'        => '.hero-bg h1',
            'render_callback' => function() { return get_theme_mod( 'hero_title' ); },
        ) );
        $wp_customize->selective_refresh->add_partial( 'hero_subtitle', array(
            'selector'        => '.hero-bg .hero-subtitle',
            'render_callback' => function() { return get_theme_mod( 'hero_subtitle' ); },
        ) );
    }
}
add_action( 'customize_register', 'viet_coffee_customize_register' );

// Sanitize float helper
function viet_coffee_sanitize_float( $value ) {
    return floatval( $value );
}

// Output dynamic CSS (colors + hero overlay) in head
function viet_coffee_customizer_css() {
    $primary = get_theme_mod( 'primary_color', '#4A2C1A' );
    $accent  = get_theme_mod( 'accent_color', '#B45309' );
    $overlay = get_theme_mod( 'hero_overlay', 0.5 );
    ?>
    <style id="viet-coffee-customizer">
        :root {
            --vc-primary: <?php echo esc_attr( $primary ); ?>;
            --vc-accent: <?php echo esc_attr( $accent ); ?>;
        }
        .bg-\[\#4A2C1A\], .bg-\[\#3a2014\], header#header,
        .text-\[\#4A2C1A\], .border-\[\#4A2C1A\],
        button.bg-\[\#4A2C1A\], .hero-bg .inline-flex {
            background-color: var(--vc-primary) !important;
            color: #fff !important;
        }
        .text-amber-700, .text-amber-600, .text-amber-800 {
            color: var(--vc-accent) !important;
        }
        .hero-bg {
            background-image: linear-gradient(rgba(0,0,0, <?php echo esc_attr( $overlay ); ?>), rgba(0,0,0, <?php echo esc_attr( $overlay ); ?>)),
                              url('<?php echo esc_url( get_theme_mod( 'hero_background', 'https://picsum.photos/id/1016/2000/1200' ) ); ?>');
        }
        .coffee-card:hover {
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.15);
        }
        #header.nav-scrolled {
            background-color: rgba(74, 44, 26, 0.98) !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'viet_coffee_customizer_css' );

// Live preview JS (for customizer)
function viet_coffee_customizer_live_preview() {
    wp_enqueue_script(
        'viet-coffee-customizer',
        get_template_directory_uri() . '/js/customizer.js',
        array( 'customize-preview' ),
        file_exists( get_template_directory() . '/js/customizer.js' ) ? filemtime( get_template_directory() . '/js/customizer.js' ) : '1.0',
        true
    );
}
add_action( 'customize_preview_init', 'viet_coffee_customizer_live_preview' );
