    <!-- FOOTER -->
    <footer class="bg-[#2C1A0F] text-amber-100 pt-16 pb-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-y-10 gap-x-6">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 text-white mb-5">
                        <i class="fa-solid fa-mug-hot text-3xl"></i>
                        <span class="text-2xl font-bold heading-font"><?php bloginfo( 'name' ); ?></span>
                    </div>
                    <p class="text-sm text-amber-200/70"><?php esc_html_e( 'Premium Vietnamese Coffee • Worldwide Shipping', 'viet-coffee' ); ?></p>
                </div>

                <!-- Dynamic widgets or fallback -->
                <div>
                    <?php if ( is_active_sidebar( 'footer-1' ) ) {
                        dynamic_sidebar( 'footer-1' );
                    } else { ?>
                        <h4 class="font-semibold mb-4 text-white"><?php esc_html_e( 'Shop', 'viet-coffee' ); ?></h4>
                        <?php if ( class_exists( 'WooCommerce' ) ) {
                            wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'space-y-2 text-sm', 'container' => false ) );
                        } ?>
                    <?php } ?>
                </div>

                <div>
                    <?php if ( is_active_sidebar( 'footer-2' ) ) {
                        dynamic_sidebar( 'footer-2' );
                    } else { ?>
                        <h4 class="font-semibold mb-4 text-white"><?php esc_html_e( 'Company', 'viet-coffee' ); ?></h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'our-story' ) ) ); ?>" class="hover:text-white"><?php esc_html_e( 'Our Story', 'viet-coffee' ); ?></a></li>
                            <li><a href="#products" class="hover:text-white"><?php esc_html_e( 'Shop', 'viet-coffee' ); ?></a></li>
                            <li><a href="#contact" class="hover:text-white"><?php esc_html_e( 'Contact', 'viet-coffee' ); ?></a></li>
                        </ul>
                    <?php } ?>
                </div>

                <div>
                    <?php if ( is_active_sidebar( 'footer-3' ) ) {
                        dynamic_sidebar( 'footer-3' );
                    } else { ?>
                        <h4 class="font-semibold mb-4 text-white"><?php esc_html_e( 'Get in Touch', 'viet-coffee' ); ?></h4>
                        <div class="space-y-1 text-sm">
                            <?php 
                            $email = get_theme_mod( 'contact_email', 'hello@vietcoffee.com' );
                            $addr  = get_theme_mod( 'contact_address', '' );
                            ?>
                            <?php if ( $email ) : ?><div><a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-white"><?php echo esc_html( $email ); ?></a></div><?php endif; ?>
                            <?php if ( $addr ) : ?><div><?php echo esc_html( $addr ); ?></div><?php endif; ?>
                        </div>

                        <!-- Social -->
                        <div class="flex gap-4 mt-5 text-xl">
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
                                    echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" class="hover:text-white"><i class="' . esc_attr( $icon ) . '"></i></a>';
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="text-center text-xs text-amber-200/50 mt-16 pt-8 border-t border-amber-900/50">
                © <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. 
                <?php echo esc_html( get_theme_mod( 'footer_copyright', 'All rights reserved. • International Shipping Available' ) ); ?>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>