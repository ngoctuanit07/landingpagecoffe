// Tailwind script init if needed
function initTailwind() {
    // Tailwind CDN script already loaded
}

// WooCommerce cart integration
function addToCartWoo(productId) {
    jQuery.post(vietCoffee.ajax_url, {
        action: 'add_to_cart',
        product_id: productId,
        nonce: vietCoffee.nonce
    }, function(response) {
        if (response.success) {
            // Update cart count
            jQuery('#cart-count').text(response.cart_count);
            // Show toast
            showToast('Added to cart!');
            // Open cart modal or proceed
            toggleCartModal();
        }
    });
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:#4A2C1A; color:white; padding:16px 24px; border-radius:9999px; z-index:200;';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 1800);
}

// Cart modal functions
let cartModalOpen = false;

function toggleCartModal() {
    // Implement modal for WooCommerce mini cart or custom
    alert('Cart modal would open here with WooCommerce mini-cart shortcode in full theme.');
    // Example: jQuery('#cart-modal').toggleClass('hidden');
}

// Buy Now - opens the confirmation modal
let buyNowProductId = 0;

function buyNow(productId, productName, productPrice) {
    buyNowProductId = productId;
    document.getElementById('buy-now-product-name').textContent = productName || '';
    document.getElementById('buy-now-product-price').textContent = productPrice || '';
    document.getElementById('buy-now-qty').value = 1;
    document.getElementById('buy-now-error').classList.add('hidden');
    document.getElementById('buy-now-loading').classList.add('hidden');
    document.getElementById('buy-now-confirm').disabled = false;
    document.getElementById('buy-now-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Additional functions for search, scroll etc.
jQuery(document).ready(function($) {
    // Scroll nav effect
    $(window).scroll(function() {
        $('#header').toggleClass('nav-scrolled', $(window).scrollTop() > 80);
    });

    // Close Buy Now modal
    $('#buy-now-close, #buy-now-overlay').on('click', function() {
        $('#buy-now-modal').addClass('hidden');
        document.body.style.overflow = '';
    });

    // Keyboard close
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#buy-now-modal').addClass('hidden');
            document.body.style.overflow = '';
        }
    });

    // Quantity buttons
    $('#buy-now-qty-minus').on('click', function() {
        const input = document.getElementById('buy-now-qty');
        if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    });
    $('#buy-now-qty-plus').on('click', function() {
        const input = document.getElementById('buy-now-qty');
        if (parseInt(input.value) < 99) input.value = parseInt(input.value) + 1;
    });

    // Confirm buy now → AJAX → redirect to checkout
    $('#buy-now-confirm').on('click', function() {
        const qty = parseInt($('#buy-now-qty').val()) || 1;
        const $btn = $(this);
        $btn.prop('disabled', true);
        $('#buy-now-loading').removeClass('hidden');
        $('#buy-now-error').addClass('hidden');

        $.post(vietCoffee.ajax_url, {
            action:     'buy_now',
            product_id: buyNowProductId,
            quantity:   qty,
            nonce:      vietCoffee.nonce
        }, function(response) {
            if (response.success) {
                window.location.href = response.data.checkout_url;
            } else {
                const msg = (response.data && response.data.message) ? response.data.message : 'Có lỗi xảy ra.';
                $('#buy-now-error').text(msg).removeClass('hidden');
                $btn.prop('disabled', false);
                $('#buy-now-loading').addClass('hidden');
            }
        }).fail(function() {
            $('#buy-now-error').text('Có lỗi xảy ra, vui lòng thử lại.').removeClass('hidden');
            $btn.prop('disabled', false);
            $('#buy-now-loading').addClass('hidden');
        });
    });
});