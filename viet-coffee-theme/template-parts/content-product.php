<?php
/**
 * Product card for custom loops - Enhanced with ratings, trust indicators
 */
global $product;
?>
<div class="coffee-card">
    <!-- Product Image Section -->
    <div class="coffee-card-image">
        <div class="relative w-full h-full">
            <?php echo $product->get_image( 'coffee-card', array( 'class' => 'w-full h-full object-cover' ) ); ?>
            
            <!-- Product Badge -->
            <?php if ( $product->is_on_sale() ) : ?>
                <div class="absolute top-4 right-4 badge badge-warning" style="background-color: #F59E0B; color: white;">
                    <?php echo esc_html( apply_filters( 'woocommerce_sale_flash', __( 'Sale', 'viet-coffee' ), $product ) ); ?>
                </div>
            <?php elseif ( $product->get_meta( 'is_new', true ) ) : ?>
                <div class="absolute top-4 right-4 badge" style="background-color: #D4A574; color: #3D2817;">
                    <?php esc_html_e( 'New', 'viet-coffee' ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Product Body -->
    <div class="coffee-card-body">
        <!-- Title -->
        <h3 class="coffee-card-title">
            <a href="<?php the_permalink(); ?>" class="hover:text-[#D4A574] transition-colors">
                <?php echo esc_html( $product->get_name() ); ?>
            </a>
        </h3>

        <!-- Description -->
        <?php if ( $product->get_short_description() ) : ?>
            <p class="coffee-card-description">
                <?php echo wp_kses_post( $product->get_short_description() ); ?>
            </p>
        <?php endif; ?>

        <!-- Rating & Reviews -->
        <?php 
        $rating = $product->get_average_rating();
        $review_count = $product->get_review_count();
        ?>
        <div class="coffee-card-rating">
            <span class="stars">
                <?php
                for ( $i = 1; $i <= 5; $i++ ) {
                    echo $i <= $rating ? '★' : '☆';
                }
                ?>
            </span>
            <span class="count">(<?php echo intval( $review_count ); ?>)</span>
        </div>

        <!-- Footer: Price & Button -->
        <div class="coffee-card-footer">
            <span class="coffee-card-price">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </span>
            
            <button data-add-to-cart="<?php echo esc_attr( $product->get_id() ); ?>" 
                    class="btn-primary" 
                    title="<?php esc_attr_e( 'Add to Cart', 'viet-coffee' ); ?>">
                <i class="fa-solid fa-cart-plus"></i>
                <span class="hidden sm:inline"><?php esc_html_e( 'Add', 'viet-coffee' ); ?></span>
            </button>
        </div>
    </div>
</div>