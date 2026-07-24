<?php
defined( 'ABSPATH' ) || exit;
global $product;
do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'viet-coffee-single-product', $product ); ?>>
    <div class="viet-product-main">
        <div class="viet-product-gallery"><?php do_action( 'woocommerce_before_single_product_summary' ); ?></div>
        <div class="summary entry-summary viet-product-summary"><?php do_action( 'woocommerce_single_product_summary' ); ?></div>
    </div>
    <?php do_action( 'woocommerce_after_single_product_summary' ); ?>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
