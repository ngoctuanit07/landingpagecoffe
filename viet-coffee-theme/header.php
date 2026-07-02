<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        .tail-container {
            font-family: 'Inter', system-ui, sans-serif;
        }
        .heading-font {
            font-family: 'Playfair Display', sans-serif;
        }
        .hero-bg {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo get_theme_mod('hero_background', 'https://picsum.photos/id/1015/2000/1200'); ?>');
            background-size: cover;
            background-position: center;
        }
        .coffee-card {
            transition: all 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
        }
        .coffee-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.15);
        }
        .nav-scrolled {
            background-color: rgba(74, 44, 26, 0.98);
            backdrop-filter: blur(12px);
        }

        /* Fallback critical layout (reduce jitter before Tailwind loads) */
        .max-w-7xl { max-width: 80rem; margin-left: auto; margin-right: auto; }
        .max-w-4xl { max-width: 56rem; margin-left: auto; margin-right: auto; }
        .max-w-3xl { max-width: 48rem; margin-left: auto; margin-right: auto; }
        .max-w-2xl { max-width: 42rem; margin-left: auto; margin-right: auto; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-8 { gap: 2rem; }
        .grid { display: grid; }
    </style>
</head>
<body <?php body_class('tail-container bg-[#f8f1e9] text-gray-800'); ?>>
    <!-- HEADER -->
    <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-[#4A2C1A] text-white">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center">
            <!-- Left spacer (balances the actions on the right to keep logo truly centered) -->
            <div class="flex-1"></div>

            <!-- Logo - centered -->
            <div class="flex items-center">
                <?php if ( function_exists( 'viet_coffee_the_logo' ) ) {
                    viet_coffee_the_logo();
                } else {
                    echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="flex items-center gap-3 text-white no-underline">';
                    echo '<i class="fa-solid fa-mug-hot text-3xl"></i>';
                    echo '<span class="text-2xl font-bold heading-font tracking-tight">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
                    echo '</a>';
                } ?>
            </div>

            <!-- Actions - right -->
            <div class="flex-1 flex items-center justify-end gap-2 md:gap-4">
                <button onclick="toggleSearch()" class="p-2 hover:bg-white/10 rounded-full transition-colors" aria-label="<?php esc_attr_e( 'Search', 'viet-coffee' ); ?>">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </button>

                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="p-2 hover:bg-white/10 rounded-full transition-colors relative" aria-label="<?php esc_attr_e( 'Cart', 'viet-coffee' ); ?>">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span id="cart-count" class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                        <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : '0'; ?>
                    </span>
                </a>
                <?php endif; ?>

            </div>
        </div>
    </header>