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

        /* Header Styling - Professional */
        #header {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #header button,
        #header a {
            cursor: pointer;
        }

        /* Logo - no underline animation (clean for centered logo) */
        #header a[href*="home"] {
            position: relative;
        }

        /* Refined action buttons hover inside toolbar */
        #header .bg-white\/5 button:hover,
        #header .bg-white\/5 a:hover {
            background-color: rgba(255, 255, 255, 0.13);
        }

        /* Fallback layout classes (kept for compatibility) */
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
            <div class="flex items-center h-full">
                
                <!-- Left spacer (for perfect logo centering) -->
                <div class="flex-1"></div>

                <!-- Logo (perfectly centered) -->
                <div class="flex items-center flex-shrink-0">
                    <?php if ( function_exists( 'viet_coffee_the_logo' ) ) {
                        viet_coffee_the_logo();
                    } else {
                        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="flex items-center gap-2.5 text-white no-underline hover:opacity-90 transition-opacity duration-200">';
                        echo '<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-xl flex items-center justify-center ring-1 ring-white/10">';
                        echo '<i class="fa-solid fa-mug-hot text-white text-base sm:text-lg"></i>';
                        echo '</div>';
                        echo '<span class="text-lg sm:text-xl font-bold heading-font tracking-tight hidden sm:block">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
                        echo '</a>';
                    } ?>
                </div>

                <!-- Right spacer + Actions -->
                <div class="flex-1 flex justify-end pl-3 sm:pl-4">
                    <!-- Professional action group -->
                    <div class="flex items-center gap-0.5 bg-white/5 rounded-2xl p-1 backdrop-blur-sm">
                        
                        <!-- Search Button -->
                        <button 
                            onclick="toggleSearch()" 
                            class="flex h-9 w-9 items-center justify-center rounded-xl text-white/90 hover:bg-white/15 hover:text-white transition-all duration-200 hover:scale-[1.03] active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40" 
                            aria-label="<?php esc_attr_e( 'Search', 'viet-coffee' ); ?>"
                            title="<?php esc_attr_e( 'Search products', 'viet-coffee' ); ?>">
                            <i class="fa-solid fa-magnifying-glass text-[15px]"></i>
                        </button>

                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <!-- Cart Button -->
                        <a 
                            href="<?php echo esc_url( wc_get_cart_url() ); ?>" 
                            class="group relative flex h-9 w-9 items-center justify-center rounded-xl text-white/90 hover:bg-white/15 hover:text-white transition-all duration-200 hover:scale-[1.03] active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40" 
                            aria-label="<?php esc_attr_e( 'Shopping Cart', 'viet-coffee' ); ?>"
                            title="<?php esc_attr_e( 'View cart', 'viet-coffee' ); ?>">
                            <i class="fa-solid fa-shopping-bag text-[15px]"></i>
                            <span id="cart-count" 
                                  class="absolute -top-0.5 -right-0.5 min-w-[17px] h-[17px] px-[4.5px] flex items-center justify-center rounded-full bg-[#4CAF50] text-white text-[9.5px] font-bold leading-none shadow-sm ring-1 ring-white/25"
                                  style="background-color: var(--color-success);">
                                <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : '0'; ?>
                            </span>
                        </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </header>
