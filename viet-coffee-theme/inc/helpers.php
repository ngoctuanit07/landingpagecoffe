<?php
/**
 * Viet Coffee Theme - Helper functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get primary color - NEW PALETTE
 */
function viet_coffee_get_primary_color() {
    return '#6B4423'; // Brown primary
}

/**
 * Get accent color
 */
function viet_coffee_get_accent_color() {
    return '#D4A574'; // Tan accent
}

/**
 * Get success color (CTA button)
 */
function viet_coffee_get_success_color() {
    return '#4CAF50'; // Green conversion color
}

/**
 * Get background cream color
 */
function viet_coffee_get_bg_color() {
    return '#FBF8F3'; // Cream background
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
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="flex items-center gap-2.5 text-white no-underline">';
        echo '<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-xl flex items-center justify-center ring-1 ring-white/10">';
        echo '<i class="fa-solid fa-mug-hot text-white text-base sm:text-lg"></i>';
        echo '</div>';
        echo '<span class="text-lg sm:text-xl font-bold heading-font tracking-tight hidden sm:block">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
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
            <input type="text" name="name" placeholder="Họ tên của bạn" required class="w-full px-4 py-3 border border-[#E8DCC9] rounded-lg focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/20" style="border-color: #E8DCC9;">
            <input type="email" name="email" placeholder="Email của bạn" required class="w-full px-4 py-3 border border-[#E8DCC9] rounded-lg focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/20" style="border-color: #E8DCC9;">
        </div>
        <textarea name="message" rows="5" placeholder="Tin nhắn của bạn..." required class="w-full px-4 py-3 border border-[#E8DCC9] rounded-lg focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/20" style="border-color: #E8DCC9;"></textarea>
        
        <button type="submit" class="w-full bg-[#4CAF50] hover:bg-[#3d8b40] transition-all text-white py-3.5 rounded-lg font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5" style="background-color: #4CAF50;">
            <i class="fa-solid fa-paper-plane mr-2"></i> <?php esc_html_e( 'Gửi tin nhắn', 'viet-coffee' ); ?>
        </button>
        <div id="contact-form-result" class="text-sm text-center h-5 font-medium"></div>
    </form>

    <script>
    (function($){
        $('#viet-coffee-contact-form').on('submit', function(e){
            e.preventDefault();
            const $form = $(this);
            const $result = $('#contact-form-result');
            const $button = $form.find('button[type=submit]');
            const originalText = $button.html();
            
            $result.removeClass('text-red-600 text-green-600').text('Đang gửi...');
            $button.prop('disabled', true).css('opacity', '0.6');

            $.post( (window.vietCoffee && vietCoffee.ajax_url) || '/wp-admin/admin-ajax.php', {
                action: 'viet_coffee_contact',
                nonce:  (window.vietCoffee && vietCoffee.nonce) || '',
                name:   $form.find('[name=name]').val(),
                email:  $form.find('[name=email]').val(),
                message:$form.find('[name=message]').val()
            }, function(res){
                if (res.success) {
                    $result.addClass('text-green-600').text('✓ ' + (res.data || 'Cảm ơn! Chúng tôi sẽ trả lời sớm.'));
                    $form[0].reset();
                    setTimeout(() => {
                        $result.text('');
                    }, 5000);
                } else {
                    $result.addClass('text-red-600').text('✗ ' + (res.data || 'Vui lòng kiểm tra lại thông tin.'));
                }
            }).fail(function(){
                $result.addClass('text-red-600').text('✗ Lỗi kết nối. Vui lòng thử lại sau.');
            }).always(function(){
                $button.prop('disabled', false).css('opacity', '1');
            });
        });
    })(jQuery);
    </script>
    <?php
}
