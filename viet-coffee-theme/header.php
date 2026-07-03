<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">
    <?php wp_head(); ?>
    <style>
        :root {
            --color-primary-brown: #6B4423;
            --color-primary-brown-dark: #3D2817;
            --color-accent-tan: #D4A574;
            --color-bg-cream: #FBF8F3;
            --color-success: #4CAF50;
            --color-white: #FFFFFF;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--color-bg-cream);
            color: #2D2D2D;
        }

        .heading-font {
            font-family: 'Playfair Display', serif;
        }

        .hero-bg {
            background-image: linear-gradient(rgba(61, 40, 23, 0.55), rgba(61, 40, 23, 0.55)), url('<?php echo get_theme_mod('hero_background', 'https://picsum.photos/id/1015/2000/1200'); ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .coffee-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .coffee-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.15);
        }

        .nav-scrolled {
            background-color: rgba(107, 68, 35, 0.98) !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        }

        /* Header Styling */
        #header {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #header button,
        #header a {
            cursor: pointer;
        }

        /* Smooth animations for header buttons */
        @keyframes headerHover {
            from {
                background-color: rgba(255, 255, 255, 0);
                transform: scale(1);
            }
            to {
                background-color: rgba(255, 255, 255, 0.15);
                transform: scale(1.05);
            }
        }

        #header button:hover,
        #header a:hover {
            animation: headerHover 0.2s ease-out;
        }

        /* Logo animation */
        a[href*="home"] {
            position: relative;
        }

        a[href*="home"]::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.5);
            transition: width 0.3s ease;
        }

        a[href*="home"]:hover::after {
            width: 100%;
        }

        /* Fallback layout classes */
        .max-w-7xl { max-width: 80rem; margin-left: auto; margin-right: auto; }
        .max-w-4xl { max-width: 56rem; margin-left: auto; margin-right: auto; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-4 { gap: 1rem; }
        .gap-8 { gap: 2rem; }
    </style>
</head>
<body <?php body_class('bg-[#FBF8F3] text-gray-800'); ?>>
    <!-- SKIP LINK FOR ACCESSIBILITY -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- HEADER -->
    <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" style="background-color: var(--color-primary-brown); border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20">
            <div class="flex items-center h-full relative">
                
                <!-- Logo (Center) -->
                <div class="absolute left-1/2 -translate-x-1/2 flex items-center flex-shrink-0">
                    <?php if ( function_exists( 'viet_coffee_the_logo' ) ) {
                        viet_coffee_the_logo();
                    } else {
                        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="flex items-center gap-2 text-white no-underline hover:opacity-90 transition-opacity duration-200">';
                        echo '<div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">';
                        echo '<i class="fa-solid fa-mug-hot text-white text-lg"></i>';
                        echo '</div>';
                        echo '<span class="text-lg font-bold heading-font tracking-tight hidden sm:block">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
                        echo '</a>';
                    } ?>
                </div>

                <!-- Right Actions (Search & Cart) -->
                <div class="flex items-center gap-1 sm:gap-2 ml-auto flex-shrink-0">
                    <!-- Search Button -->
                    <button onclick="toggleSearch()" class="p-2.5 text-white hover:bg-white/15 rounded-lg transition-all duration-200 hover:scale-105" aria-label="<?php esc_attr_e( 'Search', 'viet-coffee' ); ?>">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </button>

                    <!-- Divider -->
                    <div class="hidden sm:block w-px h-6 bg-white/20 mx-1"></div>

                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <!-- Cart Button -->
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="p-2.5 text-white hover:bg-white/15 rounded-lg transition-all duration-200 hover:scale-105 relative" aria-label="<?php esc_attr_e( 'Shopping Cart', 'viet-coffee' ); ?>">
                        <i class="fa-solid fa-shopping-bag text-lg"></i>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-[#4CAF50] text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold text-[11px] leading-none" style="background-color: var(--color-success); box-shadow: 0 2px 6px rgba(76, 175, 80, 0.4);">
                            <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : '0'; ?>
                        </span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>