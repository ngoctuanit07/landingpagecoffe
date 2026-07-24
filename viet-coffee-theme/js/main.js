/**
 * Viet Coffee Theme - Enhanced JavaScript
 * Features: Native WooCommerce add-to-cart flow, Buy Now → Checkout, Smooth scroll, Toasts, Animations
 * Add-to-cart now uses standard WC AJAX (no custom modals).
 */

'use strict';

// Responsive, keyboard-accessible hero carousel.
document.querySelectorAll('.hero-slider').forEach(function(slider) {
    const slides = Array.from(slider.querySelectorAll('.hero-slide'));
    const dots = Array.from(slider.querySelectorAll('.hero-dots button'));
    if (slides.length < 2) return;
    let current = 0;
    let timer;
    const show = function(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => slide.classList.toggle('is-active', i === current));
        dots.forEach((dot, i) => dot.classList.toggle('is-active', i === current));
    };
    const start = function() {
        window.clearInterval(timer);
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            timer = window.setInterval(() => show(current + 1), 6000);
        }
    };
    slider.querySelector('.hero-prev').addEventListener('click', () => { show(current - 1); start(); });
    slider.querySelector('.hero-next').addEventListener('click', () => { show(current + 1); start(); });
    dots.forEach((dot, i) => dot.addEventListener('click', () => { show(i); start(); }));
    slider.addEventListener('mouseenter', () => window.clearInterval(timer));
    slider.addEventListener('mouseleave', start);
    slider.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft') show(current - 1);
        if (e.key === 'ArrowRight') show(current + 1);
    });
    start();
});

// ============================================================
// HEADER SCROLL EFFECT
// ============================================================
let lastScrollTop = 0;
const header = document.getElementById('header');

window.addEventListener('scroll', function() {
    if (header) {
        const scrollPos = window.scrollY;
        
        // Add enhanced shadow and darker background on scroll
        if (scrollPos > 50) {
            header.style.boxShadow = '0 6px 20px rgba(0, 0, 0, 0.15)';
            header.classList.add('nav-scrolled');
        } else {
            header.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.08)';
            header.classList.remove('nav-scrolled');
        }
    }
});

// ============================================================
// SMOOTH SCROLL & ANCHOR LINKS
// ============================================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
            const headerHeight = 100;
            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// ============================================================
// ADD TO CART - Now handled by native WooCommerce (wc-add-to-cart)
// We only provide enhanced UX (toast + count sync) via WC events.
// ============================================================
// The custom addToCart() has been removed. Product cards now output
// standard .add_to_cart_button.ajax_add_to_cart links. WC JS handles the request.

// ============================================================
// UPDATE CART COUNT (fallback; WC fragments usually handle this automatically)
// ============================================================
function updateCartCount() {
    const cartCountEl = document.getElementById('cart-count');
    if (!cartCountEl) return;

    const ajaxUrl = (window.vietCoffee && window.vietCoffee.ajax_url) ? window.vietCoffee.ajax_url : '/wp-admin/admin-ajax.php';
    let url = ajaxUrl + '?action=get_cart_count';
    const nonce = (window.vietCoffee && window.vietCoffee.nonce) ? window.vietCoffee.nonce : '';
    if (nonce) url += '&nonce=' + encodeURIComponent(nonce);

    fetch(url, { method: 'GET' })
    .then(response => response.json())
    .then(data => {
        if (data && data.count !== undefined) {
            cartCountEl.textContent = data.count;
        }
    })
    .catch(error => console.error('Error updating cart:', error));
}

// ============================================================
// NOTIFICATIONS / TOASTS
// ============================================================
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-[#4CAF50]' : type === 'error' ? 'bg-[#EF4444]' : 'bg-[#6B4423]';
    
    toast.className = `fixed bottom-6 right-6 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 z-50 animation-scale-in`;
    // Support HTML (for checkout link in added_to_cart toast)
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="text-lg leading-none">×</button>
    `;
    
    document.body.appendChild(toast);
    
    // Longer timeout when it contains a link (give time to click checkout)
    const timeout = /<a\s/i.test(message) ? 6500 : 3200;
    setTimeout(() => {
        if (toast && toast.parentNode) {
            toast.style.animation = 'fadeOut 0.3s ease-out forwards';
            setTimeout(() => toast.remove(), 300);
        }
    }, timeout);
}

// ============================================================
// SEARCH FUNCTIONALITY
// ============================================================
function toggleSearch() {
    const searchWidget = document.getElementById('search-widget');
    if (!searchWidget) {
        // Create simple search modal
        const modal = document.createElement('div');
        modal.id = 'search-widget';
        modal.className = 'fixed inset-0 bg-black/50 z-40 flex items-start justify-center pt-20';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 p-6" onclick="event.stopPropagation()">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-magnifying-glass text-[#6B4423] text-xl"></i>
                    <input type="search" placeholder="Tìm kiếm sản phẩm..." class="flex-1 px-0 py-2 border-0 border-b-2 border-[#E8DCC9] focus:border-[#D4A574] focus:ring-0 text-lg outline-none autofocus">
                </div>
                <div id="search-results" class="mt-6"></div>
            </div>
        `;
        modal.addEventListener('click', function() {
            this.remove();
        });
        document.body.appendChild(modal);
        modal.querySelector('input').focus();
    } else {
        searchWidget.remove();
    }
}

// ============================================================
// INTERSECTION OBSERVER FOR ANIMATIONS
// ============================================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe all testimonial cards and product cards
document.querySelectorAll('.testimonial-card, .coffee-card').forEach(el => {
    observer.observe(el);
});

// ============================================================
// LAZY LOAD IMAGES
// ============================================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));
}

// ============================================================
// KEYBOARD NAVIGATION
// ============================================================
document.addEventListener('keydown', function(e) {
    // Close modals on ESC
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('[id$="-modal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    }
});

// ============================================================
// FORM VALIDATION
// ============================================================
document.addEventListener('submit', function(e) {
    if (e.target.id === 'newsletter-form') {
        e.preventDefault();
        const email = e.target.querySelector('input[type="email"]').value;
        
        if (email) {
            showNotification('✓ Thanks for subscribing!', 'success');
            e.target.reset();
        }
    }
}, true);

// ============================================================
// INITIALIZATION
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    // No custom add-to-cart binding needed — WooCommerce handles .ajax_add_to_cart

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .animation-scale-in {
            animation: scaleIn 0.3s ease-out forwards;
        }
    `;
    document.head.appendChild(style);
});

// ============================================================
// NATIVE WOOCOMMERCE ADD TO CART EVENT HANDLING
// Follows standard WooCommerce add-to-cart flow + fragments
// ============================================================
document.addEventListener('added_to_cart', function (e, fragments, cart_hash, $button) {
    // fragments may contain updated mini cart etc from WC
    updateCartCount();

    // Friendly toast. User can click header cart icon or proceed to checkout page.
    // To follow "checkout flow", the header cart goes to /cart, and /checkout is native.
    const productName = ($button && $button.data && $button.data('product_name')) || 'Product';
    showNotification('✓ ' + productName + ' added to cart. <a href="' + (window.vietCoffeeCheckoutUrl || '/checkout') + '" style="text-decoration:underline;font-weight:600;">Checkout →</a>', 'success');
});

// Provide checkout URL for toasts (localized below if needed)
if (typeof window.vietCoffee === 'undefined') {
    window.vietCoffee = {};
}
window.vietCoffeeCheckoutUrl = (window.vietCoffee && window.vietCoffee.checkout_url) ? window.vietCoffee.checkout_url : '/checkout';

// ============================================================
// BUY NOW (Direct to native WooCommerce Checkout)
// Opens confirmation modal, then on confirm: empty cart + add + redirect to /checkout
// ============================================================
let currentBuyNowProductId = null;

function buyNow(productId, productName, productPrice) {
    const modal = document.getElementById('buy-now-modal');
    if (!modal) {
        // Fallback: directly trigger native flow (add then go to checkout)
        if (productId) {
            // Use a direct add-to-cart link + redirect (non-AJAX fallback)
            window.location.href = (window.vietCoffee && window.vietCoffee.checkout_url ? window.vietCoffee.checkout_url : '/checkout') + '?add-to-cart=' + productId;
        }
        return;
    }

    currentBuyNowProductId = productId;

    // Populate modal
    modal.querySelector('#buy-now-product-name').textContent = productName || '';
    modal.querySelector('#buy-now-product-price').innerHTML = productPrice || '';
    const qtyInput = modal.querySelector('#buy-now-qty');
    if (qtyInput) qtyInput.value = 1;

    // Reset messages
    const err = modal.querySelector('#buy-now-error');
    const loading = modal.querySelector('#buy-now-loading');
    if (err) err.classList.add('hidden');
    if (loading) loading.classList.add('hidden');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Setup handlers once
    setupBuyNowHandlers(modal);
}

function buyNowQuick(productId, productName, productPrice) {
    // Quick version used from quick-view modal - same behavior
    buyNow(productId, productName, productPrice);
}

function setupBuyNowHandlers(modal) {
    if (modal.dataset.buyNowBound === 'true') return;
    modal.dataset.buyNowBound = 'true';

    const closeBtn = modal.querySelector('#buy-now-close');
    const overlay = modal.querySelector('#buy-now-overlay');
    const confirmBtn = modal.querySelector('#buy-now-confirm');
    const qtyInput = modal.querySelector('#buy-now-qty');
    const minus = modal.querySelector('#buy-now-qty-minus');
    const plus = modal.querySelector('#buy-now-qty-plus');
    const errEl = modal.querySelector('#buy-now-error');
    const loadingEl = modal.querySelector('#buy-now-loading');

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    if (closeBtn) closeBtn.onclick = closeModal;
    if (overlay) overlay.onclick = closeModal;

    if (minus && qtyInput) {
        minus.onclick = () => { qtyInput.value = Math.max(1, parseInt(qtyInput.value || 1, 10) - 1); };
    }
    if (plus && qtyInput) {
        plus.onclick = () => { qtyInput.value = Math.min(99, parseInt(qtyInput.value || 1, 10) + 1); };
    }

    if (confirmBtn) {
        confirmBtn.onclick = function () {
            if (!currentBuyNowProductId) return;

            const qty = qtyInput ? Math.max(1, parseInt(qtyInput.value, 10) || 1) : 1;

            confirmBtn.disabled = true;
            if (loadingEl) loadingEl.classList.remove('hidden');
            if (errEl) errEl.classList.add('hidden');

            const ajaxUrl = (window.vietCoffee && window.vietCoffee.ajax_url) ? window.vietCoffee.ajax_url : '/wp-admin/admin-ajax.php';
            const nonce = (window.vietCoffee && window.vietCoffee.nonce) ? window.vietCoffee.nonce : '';

            const fd = new FormData();
            fd.append('action', 'buy_now');
            fd.append('product_id', currentBuyNowProductId);
            fd.append('quantity', qty);
            fd.append('nonce', nonce);

            fetch(ajaxUrl, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.checkout_url) {
                        // Follow native WooCommerce checkout URL
                        window.location.href = data.checkout_url;
                    } else {
                        if (errEl) {
                            errEl.textContent = (data && data.data && data.data.message) || 'Could not process. Please try again.';
                            errEl.classList.remove('hidden');
                        }
                    }
                })
                .catch(() => {
                    if (errEl) {
                        errEl.textContent = 'Network error. Please try again.';
                        errEl.classList.remove('hidden');
                    }
                })
                .finally(() => {
                    confirmBtn.disabled = false;
                    if (loadingEl) loadingEl.classList.add('hidden');
                });
        };
    }

    // Close on ESC (global handler already exists)
}

console.log('%c[VietCoffee] Theme JavaScript Loaded ✓', 'color:#6B4423; font-weight:bold; font-size:14px');
