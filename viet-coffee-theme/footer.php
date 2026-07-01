    <!-- FOOTER -->
    <footer class="bg-[#2C1A0F] text-amber-100 pt-16 pb-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-10">
                <div>
                    <div class="flex items-center gap-3 text-white mb-6">
                        <i class="fa-solid fa-mug-hot text-3xl"></i>
                        <span class="text-2xl font-bold heading-font"><?php bloginfo('name'); ?></span>
                    </div>
                    <p class="text-sm text-amber-200/70">Premium Vietnamese Coffee • Worldwide Shipping</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-6 text-white">Shop</h4>
                    <?php
                    if ( class_exists( 'WooCommerce' ) ) {
                        wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'space-y-3 text-sm', 'container' => false ) );
                    }
                    ?>
                </div>
                <div>
                    <h4 class="font-semibold mb-6 text-white">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="<?php echo get_permalink( get_page_by_path( 'our-story' ) ); ?>" class="hover:text-white">Our Story</a></li>
                        <li><a href="#" class="hover:text-white">Sustainability</a></li>
                        <li><a href="#contact" class="hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-6 text-white">Get in Touch</h4>
                    <p class="text-sm">hello@vietcoffee.com</p>
                    <div class="flex gap-5 mt-6">
                        <i class="fa-brands fa-facebook text-2xl hover:text-white cursor-pointer"></i>
                        <i class="fa-brands fa-instagram text-2xl hover:text-white cursor-pointer"></i>
                    </div>
                </div>
            </div>
            
            <div class="text-center text-xs text-amber-200/50 mt-16 pt-8 border-t border-amber-900/50">
                © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved. • International Shipping Available
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>