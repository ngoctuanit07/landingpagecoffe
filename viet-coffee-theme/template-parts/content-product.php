<?php
/**
 * Product card for custom loops
 */
global $product;
?>
<div class="coffee-card bg-white border border-gray-100 rounded-3xl overflow-hidden">
    <div class="h-56 bg-amber-50 flex items-center justify-center p-6">
        <?php echo $product->get_image( 'medium' ); ?>
    </div>
    <div class="p-6">
        <h3 class="font-semibold text-lg"><?php echo $product->get_name(); ?></h3>
        <p class="text-amber-700 text-sm"><?php echo $product->get_short_description(); ?></p>
        <div class="flex justify-between items-end mt-6">
            <div class="text-2xl font-bold"><?php echo $product->get_price_html(); ?></div>
            <button onclick="addToCartWoo(<?php echo $product->get_id(); ?>)" 
                    class="bg-[#4A2C1A] text-white px-6 py-3 rounded-2xl text-sm font-medium">
                Add to Cart
            </button>
        </div>
    </div>
</div>