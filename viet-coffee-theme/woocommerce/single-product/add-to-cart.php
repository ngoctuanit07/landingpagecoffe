<?php
/**
 * Custom Buy Now for single product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<button onclick="buyNow(
        <?php echo esc_js( $product->get_id() ); ?>,
        '<?php echo esc_js( $product->get_name() ); ?>',
        '<?php echo esc_js( wp_strip_all_tags( $product->get_price_html() ) ); ?>'
    )" class="button alt wp-element-button">
    <?php esc_html_e( 'Buy Now', 'viet-coffee' ); ?>
</button>