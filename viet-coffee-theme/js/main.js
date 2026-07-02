// Tailwind script init if needed
function initTailwind() {
    // Tailwind CDN script already loaded
}

// Safety: if any old cached version of toggleCartModal is somehow still active from a previous load,
// immediately replace it with the current implementation. The real functions are defined below.
(function() {
    // Will be overwritten by the full definition later in this file, but prevents the old alert from firing.
    window.toggleCartModal = function() {
        const m = document.getElementById('cart-modal');
        if (m) {
            m.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            // Last-resort visible message instead of the old placeholder alert
            const fb = document.createElement('div');
            fb.style.cssText = 'position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.6)';
            fb.innerHTML = '<div style="background:white;padding:24px;border-radius:16px;max-width:320px;text-align:center"><strong>Cart</strong><p style="margin:12px 0">Please hard-refresh the page (Ctrl+Shift+R) to load the latest theme files.</p><button onclick="this.closest(\'[style*=\'z-index:99999\']\').remove();document.body.style.overflow=\'\'" style="background:#4A2C1A;color:white;padding:8px 20px;border-radius:9999px">Close</button></div>';
            document.body.appendChild(fb);
        }
    };

    // Safety stubs for new checkout functions (prevent JS errors from cached bundles)
    window.closeCartAndOpenCheckout = function() {
        const m = document.getElementById('cart-modal');
        if (m) m.classList.add('hidden');
        document.body.style.overflow = '';
        // If real function not present yet, show message
        if (typeof toggleCheckoutModal !== 'function') {
            const fb = document.createElement('div');
            fb.style.cssText = 'position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.6)';
            fb.innerHTML = '<div style="background:white;padding:24px;border-radius:16px;max-width:340px;text-align:center"><strong>Checkout</strong><p style="margin:12px 0">Please hard-refresh (Ctrl+Shift+R) to load the modal checkout.</p><button onclick="this.closest(\'[style*=\'z-index:99999\']\').remove();document.body.style.overflow=\'\'" style="background:#4A2C1A;color:white;padding:8px 20px;border-radius:9999px">Close</button></div>';
            document.body.appendChild(fb);
        } else {
            toggleCheckoutModal();
        }
    };

    window.toggleCheckoutModal = window.toggleCheckoutModal || function() {
        const m = document.getElementById('checkout-modal');
        if (m) {
            m.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            const fb = document.createElement('div');
            fb.style.cssText = 'position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.6)';
            fb.innerHTML = '<div style="background:white;padding:24px;border-radius:16px;max-width:340px;text-align:center"><strong>Checkout</strong><p style="margin:12px 0">Please hard-refresh the page (Ctrl+Shift+R) to load the latest checkout modal.</p><a href=\"/checkout\" style=\"color:#4A2C1A;text-decoration:underline\">Open full checkout page</a></div>';
            document.body.appendChild(fb);
        }
    };

    window.closeCheckoutAndOpenCart = window.closeCheckoutAndOpenCart || function() {
        const m = document.getElementById('checkout-modal');
        if (m) m.classList.add('hidden');
        document.body.style.overflow = '';
        setTimeout(function() {
            if (typeof toggleCartModal === 'function') toggleCartModal();
        }, 50);
    };
})();

// WooCommerce cart integration
function addToCartWoo(productId) {
    const ajaxUrl = (window.vietCoffee && vietCoffee.ajax_url) ? vietCoffee.ajax_url : '/wp-admin/admin-ajax.php';
    const nonce   = (window.vietCoffee && vietCoffee.nonce)   ? vietCoffee.nonce   : '';

    jQuery.post(ajaxUrl, {
        action: 'add_to_cart',
        product_id: productId,
        nonce: nonce
    }, function(response) {
        if (response && response.success) {
            const count = (response.data && response.data.cart_count) ? response.data.cart_count : 0;
            jQuery('#cart-count').text(count);
            showToast('Added to cart!');
            jQuery(document.body).trigger('added_to_cart');
            toggleCartModal();
        } else {
            const msg = (response && response.data && response.data.message) ? response.data.message : 'Could not add to cart.';
            showToast(msg);
        }
    }).fail(function() {
        showToast('Network error. Please try again.');
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
function toggleCartModal() {
    let modal = document.getElementById('cart-modal');

    if (!modal) {
        // Fallback: create a basic cart modal if the server-rendered one is missing (old cache or edge case)
        console.warn('VietCoffee: #cart-modal not found in DOM, creating fallback.');
        modal = document.createElement('div');
        modal.id = 'cart-modal';
        modal.className = 'fixed inset-0 z-[9999]';
        modal.innerHTML = `
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
                    <h2 class="text-2xl font-bold mb-4 text-[#4A2C1A]">Cart</h2>
                    <p class="mb-4">This is a fallback cart view. In the full theme this would show the WooCommerce mini-cart.</p>
                    <div class="flex gap-3">
                        <a href="/cart" class="flex-1 text-center px-6 py-3 rounded-2xl border">View Cart</a>
                        <button onclick="closeCartAndOpenCheckout()" class="flex-1 text-center px-6 py-3 rounded-2xl bg-[#4A2C1A] text-white">Checkout</button>
                    </div>
                    <button onclick="this.closest('#cart-modal').classList.add('hidden');document.body.style.overflow='';" 
                            class="mt-4 text-sm text-gray-500">Close</button>
                </div>
            </div>`;
        document.body.appendChild(modal);
    }

    const isOpen = !modal.classList.contains('hidden');

    if (!isOpen) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        // Only try to refresh if we have the proper content container (real modal)
        if (document.getElementById('cart-modal-content') && window.vietCoffee) {
            refreshMiniCartContent();
        }
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

console.log('%c[VietCoffee] Cart modal JS loaded (fixed version)', 'color:#4A2C1A');

function refreshMiniCartContent() {
    const content = document.getElementById('cart-modal-content');
    if (!content) return;
    if (!window.vietCoffee || !vietCoffee.ajax_url) {
        content.innerHTML = '<p class="text-center py-6">Cart data not available.</p>';
        return;
    }

    content.innerHTML = '<div class="py-8 text-center text-gray-500">Loading cart...</div>';

    jQuery.post(vietCoffee.ajax_url, {
        action: 'get_mini_cart',
        nonce: vietCoffee.nonce || ''
    }, function(response) {
        if (response && response.success && response.data) {
            // Wrap in the class WooCommerce fragments target, if not already
            let html = response.data;
            if (html.indexOf('widget_shopping_cart_content') === -1) {
                html = '<div class="widget_shopping_cart_content">' + html + '</div>';
            }
            content.innerHTML = html;

            // Notify WooCommerce so remove/update buttons and other listeners work
            jQuery(document.body).trigger('wc_fragments_refreshed');
            jQuery(document.body).trigger('wc_fragments_loaded');
        } else {
            content.innerHTML = '<p class="text-center py-6 text-red-500">Could not load cart contents.</p>';
        }
    }).fail(function() {
        content.innerHTML = '<p class="text-center py-6 text-red-500">Error loading cart.</p>';
    });
}

// ============================================================
// Checkout modal support (close cart -> open checkout modal with real WC form)
// ============================================================
function closeCartAndOpenCheckout() {
    const cartModal = document.getElementById('cart-modal');
    if (cartModal) cartModal.classList.add('hidden');
    document.body.style.overflow = '';
    toggleCheckoutModal();
}

function closeCheckoutAndOpenCart() {
    const checkoutModal = document.getElementById('checkout-modal');
    if (checkoutModal) checkoutModal.classList.add('hidden');
    document.body.style.overflow = '';
    setTimeout(function() {
        if (typeof toggleCartModal === 'function') toggleCartModal();
    }, 40);
}

function toggleCheckoutModal() {
    let modal = document.getElementById('checkout-modal');

    if (!modal) {
        console.warn('VietCoffee: #checkout-modal not found, creating fallback.');
        modal = document.createElement('div');
        modal.id = 'checkout-modal';
        modal.className = 'fixed inset-0 z-[9999]';
        modal.innerHTML = `
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-8">
                    <h2 class="text-2xl font-bold mb-4 text-[#4A2C1A]">Checkout</h2>
                    <p class="mb-4 text-sm">Please hard-refresh the page (Ctrl+Shift+R) to load the latest checkout form.</p>
                    <a href="/checkout" class="inline-block px-6 py-3 rounded-2xl bg-[#4A2C1A] text-white font-semibold">Go to full Checkout page</a>
                    <button onclick="this.closest('#checkout-modal').classList.add('hidden');document.body.style.overflow='';" class="block mt-4 text-sm text-gray-500">Close</button>
                </div>
            </div>`;
        document.body.appendChild(modal);
    }

    const isOpen = !modal.classList.contains('hidden');

    if (!isOpen) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        const contentEl = document.getElementById('checkout-modal-content');
        if (contentEl) contentEl.scrollTop = 0;
        if (contentEl && window.vietCoffee) {
            refreshCheckoutContent();
        }
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function refreshCheckoutContent() {
    const content = document.getElementById('checkout-modal-content');
    if (!content) return;
    if (!window.vietCoffee || !vietCoffee.ajax_url) {
        content.innerHTML = '<p class="text-center py-6">Checkout data not available.</p>';
        return;
    }

    content.innerHTML = '<div class="py-8 text-center text-gray-500">Loading checkout form...</div>';

    jQuery.post(vietCoffee.ajax_url, {
        action: 'get_checkout',
        nonce: vietCoffee.nonce || ''
    }, function(response) {
        if (response && response.success && response.data) {
            content.innerHTML = response.data;

            // Make the checkout form post to *current* page. This allows WC_Form_Handler
            // (which runs on every wp_loaded) to process the order using the embedded form context.
            // Success will redirect (to thank you), errors/notices may stay usable.
            var $form = jQuery('#checkout-modal-content form.checkout');
            if ($form.length) {
                // Use current URL (with hash stripped) so POST targets this page
                try {
                    var actionUrl = window.location.origin + window.location.pathname + window.location.search;
                    $form.attr('action', actionUrl);
                } catch (e) {}
            }

            // Trigger WooCommerce events so payment methods, validation, address i18n, etc initialize
            jQuery(document.body).trigger('updated_checkout');
            jQuery(document.body).trigger('wc_fragments_refreshed');

            // After dynamic insert, re-init country/state fields and give WC a chance to bind
            setTimeout(function() {
                jQuery('#checkout-modal-content #billing_country, #checkout-modal-content #shipping_country')
                    .trigger('change');
                jQuery(document.body).trigger('updated_checkout');

                // If there are notices from server (e.g. previous failed attempt) make sure visible
                var $notices = jQuery('#checkout-modal-content .woocommerce-error, #checkout-modal-content .woocommerce-message');
                if ($notices.length) {
                    $notices.get(0).scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 180);
        } else {
            content.innerHTML = '<p class="text-center py-6 text-red-500">Could not load checkout form.</p>';
        }
    }).fail(function() {
        content.innerHTML = '<p class="text-center py-6 text-red-500">Error loading checkout.</p>';
    });
}

console.log('%c[VietCoffee] Checkout modal support loaded', 'color:#4A2C1A');

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

// Smooth scroll to section (accounts for fixed header)
function scrollToSection(hash) {
    if (!hash) return;
    const target = document.querySelector(hash);
    if (!target) return;

    const headerOffset = 90; // fixed header + breathing room
    const elementPosition = target.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

    window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
    });
}

// Global handler: make all menu links with # scroll smoothly on same page
function initSmoothScroll($) {
    $('a[href*="#"]').on('click', function(e) {
        const href = $(this).attr('href') || '';
        if (!href || href === '#') return;

        const hashIndex = href.indexOf('#');
        if (hashIndex === -1) return;

        const hash = href.substring(hashIndex);
        if (hash.length <= 1) return;

        // Determine if we are staying on current page
        let willNavigate = false;
        try {
            const url = new URL(href, window.location.href);
            const sameOrigin = url.origin === window.location.origin;
            const samePath = url.pathname.replace(/\/$/, '') === window.location.pathname.replace(/\/$/, '');
            if (!sameOrigin || !samePath) {
                // Different page: allow normal browser navigation to url#hash
                return;
            }
        } catch (err) {
            // Relative or hash-only: proceed to scroll attempt
        }

        // Prevent default and attempt smooth scroll on current page
        e.preventDefault();

        // Close open modals
        $('#cart-modal, #search-modal').addClass('hidden');
        document.body.style.overflow = '';

        const targetEl = document.querySelector(hash);
        if (targetEl) {
            scrollToSection(hash);
            if (history.pushState) {
                history.pushState(null, null, hash);
            } else {
                location.hash = hash;
            }
        } else {
            // Target missing (e.g. on non-front page), navigate
            window.location.href = href;
        }
    });
}

// Search modal
function toggleSearch() {
    const modal = document.getElementById('search-modal');
    const input = document.getElementById('site-search-input');
    if (!modal) {
        // Fallback
        window.location.href = '/?post_type=product';
        return;
    }
    const isHidden = modal.classList.contains('hidden');
    if (isHidden) {
        modal.classList.remove('hidden');
        if (input) {
            setTimeout(() => input.focus(), 50);
        }
    } else {
        modal.classList.add('hidden');
    }
}

// Init everything
jQuery(document).ready(function($) {
    // Scroll nav effect
    $(window).scroll(function() {
        $('#header').toggleClass('nav-scrolled', $(window).scrollTop() > 80);
    });
    // Apply correct state immediately (prevents small flash on hard refresh)
    $('#header').toggleClass('nav-scrolled', $(window).scrollTop() > 80);

    // Init smooth scrolling for nav / anchors
    initSmoothScroll($);

    // Sync header cart count after Woo cart changes (e.g. remove inside modal)
    function updateCartCount() {
        if (!window.vietCoffee) return;
        $.post(vietCoffee.ajax_url, {
            action: 'get_cart_count',
            nonce: vietCoffee.nonce
        }, function(res) {
            if (res.success && typeof res.data.count !== 'undefined') {
                $('#cart-count').text(res.data.count);
            }
        });
    }
    $(document.body).on('added_to_cart removed_from_cart wc_fragments_refreshed wc_fragments_loaded', function() {
        updateCartCount();
    });

    // Close Buy Now modal
    $('#buy-now-close, #buy-now-overlay').on('click', function() {
        $('#buy-now-modal').addClass('hidden');
        document.body.style.overflow = '';
    });

    // Cart modal close
    $('#cart-close, #cart-overlay').on('click', function() {
        $('#cart-modal').addClass('hidden');
        document.body.style.overflow = '';
    });

    // Checkout modal close
    $('#checkout-close, #checkout-overlay').on('click', function() {
        $('#checkout-modal').addClass('hidden');
        document.body.style.overflow = '';
    });

    // Click outside panel also handled by overlay above, but extra safety for panel clicks not propagating
    $('#checkout-modal').on('click', function(e) {
        if (e.target.id === 'checkout-modal') {
            $('#checkout-modal').addClass('hidden');
            document.body.style.overflow = '';
        }
    });

    // Search modal
    $('#search-close, #search-overlay').on('click', function() {
        $('#search-modal').addClass('hidden');
    });
    // Submit already handled by form action. On close also clear
    $('#search-modal').on('click', function(e) {
        if (e.target.id === 'search-modal') $('#search-modal').addClass('hidden');
    });

    // Keyboard close (buy-now + cart + checkout + search)
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            const $buy = $('#buy-now-modal');
            const $checkout = $('#checkout-modal');
            const $cart = $('#cart-modal');
            const $search = $('#search-modal');
            if (!$buy.hasClass('hidden')) {
                $buy.addClass('hidden');
            } else if (!$checkout.hasClass('hidden')) {
                $checkout.addClass('hidden');
            } else if (!$cart.hasClass('hidden')) {
                $cart.addClass('hidden');
            } else if (!$search.hasClass('hidden')) {
                $search.addClass('hidden');
            }
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

    // Delegated "Add to Cart" handler
    $(document).on('click', '[data-add-to-cart]', function(e) {
        e.preventDefault();
        const pid = parseInt( $(this).data('add-to-cart'), 10 );
        if (pid > 0) {
            addToCartWoo(pid);
        }
    });

    // Checkout intercept inside cart modal
    $(document).on('click', '#cart-modal a[href*="checkout"], #cart-modal .checkout, #cart-modal button.checkout', function(e) {
        const onclickAttr = $(this).attr('onclick') || '';
        if (onclickAttr.indexOf('closeCartAndOpenCheckout') !== -1) {
            return;
        }
        e.preventDefault();
        closeCartAndOpenCheckout();
    });

    // === Professional Mobile Menu ===
    const $mobileToggle = $('#mobile-menu-toggle');
    const $mobileMenu   = $('#mobile-menu');

    function closeMobileMenu() {
        $mobileMenu.removeClass('open');
        $mobileToggle.attr('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
    window.closeMobileMenu = closeMobileMenu;

    $mobileToggle.on('click', function() {
        const isOpen = $mobileMenu.hasClass('open');
        if (isOpen) {
            closeMobileMenu();
        } else {
            $mobileMenu.addClass('open');
            $mobileToggle.attr('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }
    });

    // Close mobile menu on link click
    $mobileMenu.on('click', 'a', function() {
        setTimeout(closeMobileMenu, 120);
    });

    // Close on overlay/escape (overlay is the body click outside)
    $(document).on('click', function(e) {
        if ($mobileMenu.hasClass('open') && !$(e.target).closest('#mobile-menu, #mobile-menu-toggle').length) {
            closeMobileMenu();
        }
    });

    // Quick view support (from woocommerce.php quick view)
    window.buyNowQuick = function(pid, name, price) {
        // reuse existing buyNow modal
        if (typeof buyNow === 'function') {
            buyNow(pid, name, price);
        } else {
            window.location.href = '/?add-to-cart=' + pid;
        }
    };

    // Optional quick view button support (add data-quick-view on future buttons)
    $(document).on('click', '[data-quick-view]', function(e) {
        e.preventDefault();
        const pid = $(this).data('quick-view');
        if (!pid || !window.vietCoffee) return;

        $.post(vietCoffee.ajax_url, {
            action: 'viet_coffee_quick_view',
            product_id: pid,
            nonce: vietCoffee.nonce
        }, function(res) {
            if (res.success) {
                // Show simple modal using existing pattern
                let qv = document.getElementById('quick-view-modal');
                if (!qv) {
                    qv = document.createElement('div');
                    qv.id = 'quick-view-modal';
                    qv.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60';
                    document.body.appendChild(qv);
                }
                qv.innerHTML = `<div class="bg-white rounded-3xl max-w-3xl w-full p-8 relative">${res.data}<button class="absolute top-4 right-5 text-3xl text-gray-400" onclick="document.getElementById('quick-view-modal').remove()">&times;</button></div>`;
                qv.onclick = (ev) => { if (ev.target.id === 'quick-view-modal') qv.remove(); };
            }
        });
    });
});