/**
 * Header Loader Script
 * Dynamically injects header content from header-data.js into pages
 */

function loadSharedHeader() {
    const placeholder = document.getElementById('header-placeholder');
    if (!placeholder) return;

    // headerHTML is defined in assets/js/header-data.js
    if (typeof headerHTML !== 'undefined') {
        placeholder.innerHTML = headerHTML;
        initializeHeader();
    } else {
        console.error('headerHTML is not defined. Make sure header-data.js is loaded before header-loader.js');
    }

    function initializeHeader() {
        const currentPath = window.location.pathname.split('/').pop() || 'index.html';

        // Handle Active Links for Desktop and Mobile
        const allLinks = document.querySelectorAll('.argo-nav-bar nav ul li a, .mobile-menu-sidebar .navigation li a');
        allLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPath) {
                link.parentElement.classList.add('current');
                // Also handle parent dropdowns if necessary
                let parent = link.closest('.argo-dropdown, .dropdown');
                if (parent) {
                    parent.classList.add('current');
                }
            }
        });

        // Initialize Mobile Menu Toggles
        if (window.jQuery) {
            const $ = window.jQuery;

            // Toggle Sidebar
            $('.argo-mobile-header .mobile-nav-toggler, .mobile-menu-sidebar .close-btn, .mobile-menu-sidebar .menu-backdrop').on('click', function () {
                $('body').toggleClass('mobile-menu-visible');
            });

            // Dropdown Toggle in Sidebar
            $('.mobile-menu-sidebar .navigation li.dropdown > a').off('click').on('click', function (e) {
                if ($(this).next('ul').length) {
                    e.preventDefault();
                    $(this).parent('li').toggleClass('open');
                    $(this).next('ul').slideToggle(500);
                }
            });
        }

        // Logic for "Get a free quote" button
        function updateQuoteButton() {
            const quoteBtn = document.querySelector('.argo-cta');
            if (quoteBtn) {
                if (window.innerWidth > 991) {
                    // Desktop: Send Email
                    quoteBtn.setAttribute('href', 'mailto:sohny@homebysohny.com');
                } else {
                    // Mobile/Tablet: Call Phone
                    quoteBtn.setAttribute('href', 'tel:+19255239723');
                }
            }
        }

        // Run on load
        updateQuoteButton();

        // Run on resize
        window.addEventListener('resize', updateQuoteButton);
    }
}

// Run immediately or on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadSharedHeader);
} else {
    loadSharedHeader();
}
