/**
 * Viet Coffee Theme - Enhanced JavaScript
 * Features: Mobile menu toggle, Smooth scrolling, Cart modal, Checkout modal, Animations
 */

'use strict';

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
// ADD TO CART FUNCTIONALITY
// ============================================================
function addToCart(productId) {
    if (!productId) return;

    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', 1);
    formData.append('action', 'add_to_cart_woo');
    formData.append('nonce', (window.vietCoffee && window.vietCoffee.nonce) ? window.vietCoffee.nonce : '');

    // Update button state
    const buttons = document.querySelectorAll(`button[data-add-to-cart="${productId}"]`);
    buttons.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    });

    const ajaxUrl = (window.vietCoffee && window.vietCoffee.ajax_url) ? window.vietCoffee.ajax_url : '/wp-admin/admin-ajax.php';

    fetch(ajaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('✓ Added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification('✗ Could not add to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('✗ Network error', 'error');
    })
    .finally(() => {
        // Restore button state
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i><span class="hidden sm:inline">Add</span>';
        });
    });
}

// ============================================================
// UPDATE CART COUNT
// ============================================================
function updateCartCount() {
    const cartCountEl = document.getElementById('cart-count');
    if (!cartCountEl) return;

    const ajaxUrl = (window.vietCoffee && window.vietCoffee.ajax_url) ? window.vietCoffee.ajax_url : '/wp-admin/admin-ajax.php';

    fetch(ajaxUrl + '?action=get_cart_count', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.count !== undefined) {
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
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="text-lg leading-none">×</button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
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
    // Add click handlers to all add-to-cart buttons
    document.querySelectorAll('button[data-add-to-cart]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            addToCart(this.getAttribute('data-add-to-cart'));
        });
    });

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

// WooCommerce cart fragments update
document.addEventListener('added_to_cart', function() {
    updateCartCount();
});

console.log('%c[VietCoffee] Theme JavaScript Loaded ✓', 'color:#6B4423; font-weight:bold; font-size:14px');
