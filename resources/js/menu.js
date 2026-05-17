// resources/js/menu.js

/**
 * Handles the mobile menu toggle logic and click-outside behavior.
 */
export function initMobileMenu() {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (!menuToggle || !mobileMenu) return;

    // Toggle Menu on Button Click
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        const isClosed = mobileMenu.classList.contains('max-h-0');
        
        // Toggle visibility classes
        mobileMenu.classList.toggle('max-h-0', !isClosed);
        mobileMenu.classList.toggle('opacity-0', !isClosed);
        mobileMenu.classList.toggle('max-h-screen', isClosed);
        mobileMenu.classList.toggle('opacity-100', isClosed);
    });

    // Close Menu when clicking anywhere outside the menu or toggle button
    window.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            mobileMenu.classList.add('max-h-0', 'opacity-0');
            mobileMenu.classList.remove('max-h-screen', 'opacity-100');
        }
    });
}