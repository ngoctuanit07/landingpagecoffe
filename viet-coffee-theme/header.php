<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap');
        
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
    </style>
</head>
<body <?php body_class('tail-container bg-[#f8f1e9] text-gray-800'); ?>>
    <!-- HEADER -->
    <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-[#4A2C1A] text-white">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-mug-hot text-3xl"></i>
                <div class="text-2xl font-bold heading-font tracking-tight"><?php bloginfo('name'); ?></div>
            </div>
            
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class' => '',
                    'container' => false,
                    'items_wrap' => '%3$s',
                    'fallback_cb' => false,
                ) );
                ?>
            </nav>

            <div class="flex items-center gap-4">
                <button onclick="toggleSearch()" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </button>
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo wc_get_cart_url(); ?>" class="p-2 hover:bg-white/10 rounded-full transition-colors relative">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span id="cart-count" class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </header>