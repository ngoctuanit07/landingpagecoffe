<?php
/**
 * Single Product Add to Cart + Buy Now (native WooCommerce flow for Add to Cart)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}
?>
<div class="flex flex-col sm:flex-row gap-3 items-start">
	<?php
	// Load the inner native add-to-cart template directly (avoids recursion from overriding add-to-cart.php).
	// This gives full native WooCommerce quantity + Add to Cart button behavior.
	if ( $product->is_type( 'simple' ) || $product->is_type( 'subscription' ) ) {
		wc_get_template( 'single-product/add-to-cart/simple.php' );
	} else {
		// Variable, grouped, external etc. — let WC handle via action (safe here)
		do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
	}
	?>

	<?php if ( $product->is_in_stock() ) : ?>
		<button type="button"
		        onclick="buyNow(<?php echo esc_js( $product->get_id() ); ?>, '<?php echo esc_js( $product->get_name() ); ?>', '<?php echo esc_js( wp_strip_all_tags( $product->get_price_html() ) ); ?>')"
		        class="button alt wp-element-button" style="background:#4A2C1A; color:white; border-color:#4A2C1A; margin-top: 4px;">
			<?php esc_html_e( 'Buy Now', 'viet-coffee' ); ?>
		</button>
	<?php endif; ?>
</div>