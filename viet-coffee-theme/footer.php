    <!-- FOOTER -->
    <footer class="bg-gradient-to-r from-[#6B4423] to-[#3D2817] text-amber-100 pt-16 pb-10" style="background: linear-gradient(180deg, #6B4423 0%, #3D2817 100%);">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Footer Grid -->
            <div class="grid md:grid-cols-4 gap-y-10 gap-x-8 mb-12">
                <!-- Brand Column -->
                <div>
                    <div class="flex items-center gap-3 text-white mb-4">
                        <i class="fa-solid fa-mug-hot text-2xl" style="color: #D4A574;"></i>
                        <div>
                            <h4 class="font-bold heading-font text-lg"><?php bloginfo( 'name' ); ?></h4>
                            <p class="text-xs text-amber-200 opacity-80 mt-0"><?php bloginfo( 'description' ); ?></p>
                        </div>
                    </div>
                    <p class="text-sm text-amber-100 opacity-75 leading-relaxed mt-4"><?php echo esc_html( get_theme_mod( 'footer_about', 'Premium Vietnamese Coffee, sourced directly from highlands and roasted fresh daily for global coffee lovers.' ) ); ?></p>
                </div>

                <!-- Shop Column -->
                <div>
                    <?php if ( is_active_sidebar( 'footer-1' ) ) {
                        dynamic_sidebar( 'footer-1' );
                    } else { ?>
                        <h4 class="font-semibold mb-4 text-[#D4A574]" style="color: #D4A574;"><?php esc_html_e( 'Shop', 'viet-coffee' ); ?></h4>
                        <ul class="space-y-2.5 text-sm">
                            <?php if ( class_exists( 'WooCommerce' ) ) {
                                echo wp_nav_menu( array( 
                                    'theme_location' => 'primary', 
                                    'container'      => false,
                                    'echo'           => false,
                                    'depth'          => 1,
                                    'fallback_cb'    => 'wp_page_menu',
                                    'menu_class'     => 'space-y-2.5 text-sm',
                                ) );
                            } ?>
                        </ul>
                    <?php } ?>
                </div>

                <!-- Company Column -->
                <div>
                    <?php if ( is_active_sidebar( 'footer-2' ) ) {
                        dynamic_sidebar( 'footer-2' );
                    } else { ?>
                        <h4 class="font-semibold mb-4 text-[#D4A574]" style="color: #D4A574;"><?php esc_html_e( 'Company', 'viet-coffee' ); ?></h4>
                        <ul class="space-y-2.5 text-sm">
                            <li><a href="<?php echo esc_url( get_home_url() . '/about' ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'About Us', 'viet-coffee' ); ?></a></li>
                            <li><a href="<?php echo esc_url( get_home_url() . '/blog' ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'Blog', 'viet-coffee' ); ?></a></li>
                            <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'Shop', 'viet-coffee' ); ?></a></li>
                            <li><a href="<?php echo esc_url( get_home_url() . '/contact' ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'Contact', 'viet-coffee' ); ?></a></li>
                        </ul>
                    <?php } ?>
                </div>

                <!-- Contact & Social Column -->
                <div>
                    <?php if ( is_active_sidebar( 'footer-3' ) ) {
                        dynamic_sidebar( 'footer-3' );
                    } else { ?>
                        <h4 class="font-semibold mb-4 text-[#D4A574]" style="color: #D4A574;"><?php esc_html_e( 'Get in Touch', 'viet-coffee' ); ?></h4>
                        <div class="space-y-2.5 text-sm mb-6">
                            <?php 
                            $email = get_theme_mod( 'contact_email', 'hello@vietcoffee.com' );
                            $phone = get_theme_mod( 'contact_phone', '' );
                            $addr  = get_theme_mod( 'contact_address', '' );
                            ?>
                            <?php if ( $email ) : ?>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-envelope text-[#D4A574]" style="color: #D4A574; flex-shrink: 0;"></i>
                                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $email ); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if ( $phone ) : ?>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-phone text-[#D4A574]" style="color: #D4A574; flex-shrink: 0;"></i>
                                    <a href="tel:<?php echo esc_attr( $phone ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $phone ); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if ( $addr ) : ?>
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-location-dot text-[#D4A574] mt-0.5" style="color: #D4A574; flex-shrink: 0;"></i>
                                    <span><?php echo esc_html( $addr ); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Social Links -->
                        <div class="flex gap-3 text-xl">
                            <?php 
                            $socials = array(
                                'social_facebook'  => 'fa-brands fa-facebook',
                                'social_instagram' => 'fa-brands fa-instagram',
                                'social_tiktok'    => 'fa-brands fa-tiktok',
                                'social_youtube'   => 'fa-brands fa-youtube',
                            );
                            foreach ( $socials as $key => $icon ) {
                                $url = get_theme_mod( $key );
                                if ( $url ) {
                                    echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" class="hover:text-[#D4A574] transition-colors" style="color: rgba(255,255,255,0.85);" title="' . esc_attr( ucwords( str_replace( array( '_', 'social' ), array( ' ', '' ), $key ) ) ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="text-center text-xs text-amber-100 opacity-60 pt-8 border-t border-white/10">
                <p class="mb-2">© <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php echo esc_html( get_theme_mod( 'footer_copyright', 'All rights reserved. • International Shipping Available' ) ); ?></p>
                <div class="space-x-4 text-sm">
                    <a href="<?php echo esc_url( get_home_url() . '/privacy' ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'Privacy Policy', 'viet-coffee' ); ?></a>
                    <span>•</span>
                    <a href="<?php echo esc_url( get_home_url() . '/terms' ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'Terms & Conditions', 'viet-coffee' ); ?></a>
                    <span>•</span>
                    <a href="<?php echo esc_url( get_home_url() . '/shipping' ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'Shipping Policy', 'viet-coffee' ); ?></a>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>