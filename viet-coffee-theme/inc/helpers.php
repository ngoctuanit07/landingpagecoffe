<?php
/**
 * Viet Coffee Theme - Helper functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get primary color
 */
function viet_coffee_get_primary_color() {
    return get_theme_mod( 'primary_color', '#4A2C1A' );
}

/**
 * Simple trust bar items
 */
function viet_coffee_get_trust_items() {
    return apply_filters( 'viet_coffee_trust_items', array(
        array( 'icon' => 'fa-truck-fast', 'text' => __( 'Free Shipping over $50', 'viet-coffee' ) ),
        array( 'icon' => 'fa-seedling',   'text' => __( 'Freshly Roasted Daily', 'viet-coffee' ) ),
        array( 'icon' => 'fa-award',      'text' => __( 'Direct Trade Sourced', 'viet-coffee' ) ),
        array( 'icon' => 'fa-globe',      'text' => __( 'Ships Worldwide', 'viet-coffee' ) ),
    ) );
}

/**
 * Output logo
 */
function viet_coffee_the_logo() {
    if ( has_custom_logo() ) {
        the_custom_logo();
    } else {
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="flex items-center gap-3 text-white no-underline">';
        echo '<i class="fa-solid fa-mug-hot text-3xl"></i>';
        echo '<span class="text-2xl font-bold heading-font tracking-tight">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
        echo '</a>';
    }
}

/**
 * Simple native contact form (AJAX + wp_mail) - no external plugin needed
 */
function viet_coffee_render_contact_form() {
    ?>
    <form id="viet-coffee-contact-form" class="space-y-4" novalidate>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="name" placeholder="<?php esc_attr_e( 'Your name', 'viet-coffee' ); ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#4A2C1A]">
            <input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email', 'viet-coffee' ); ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#4A2C1A]">
        </div>
        <textarea name="message" rows="4" placeholder="<?php esc_attr_e( 'How can we help you?', 'viet-coffee' ); ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#4A2C1A]"></textarea>
        
        <button type="submit" class="w-full bg-[#4A2C1A] hover:bg-black transition-colors text-white py-3.5 rounded-2xl font-semibold">
            <?php esc_html_e( 'Send Message', 'viet-coffee' ); ?>
        </button>
        <div id="contact-form-result" class="text-sm text-center h-5"></div>
    </form>

    <script>
    (function($){
        $('#viet-coffee-contact-form').on('submit', function(e){
            e.preventDefault();
            const $form = $(this);
            const $result = $('#contact-form-result');
            $result.text('<?php echo esc_js( __( 'Sending...', 'viet-coffee' ) ); ?>').removeClass('text-red-600 text-green-600');

            $.post( (window.vietCoffee && vietCoffee.ajax_url) || '/wp-admin/admin-ajax.php', {
                action: 'viet_coffee_contact',
                nonce:  (window.vietCoffee && vietCoffee.nonce) || '',
                name:   $form.find('[name=name]').val(),
                email:  $form.find('[name=email]').val(),
                message:$form.find('[name=message]').val()
            }, function(res){
                if (res.success) {
                    $result.addClass('text-green-600').text(res.data || 'Thank you! We will reply soon.');
                    $form[0].reset();
                } else {
                    $result.addClass('text-red-600').text(res.data || 'Please check your input.');
                }
            }).fail(function(){
                $result.addClass('text-red-600').text('<?php echo esc_js( __( 'Network error. Please try again later.', 'viet-coffee' ) ); ?>');
            });
        });
    })(jQuery);
    </script>
    <?php
}
