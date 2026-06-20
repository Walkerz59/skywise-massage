// ── Image Protection (inspired by homelegance.com) ─────────────────────────
// Block right-click context menu on ALL images site-wide
document.addEventListener('contextmenu', function (e) {
    if (e.target.tagName === 'IMG' || e.target.classList.contains('pd-img-shield')) {
        e.preventDefault();
        return false;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const images = document.querySelectorAll('img');

    images.forEach(function (img) {
        // Prevent drag-to-desktop
        img.setAttribute('draggable', 'false');
        img.addEventListener('contextmenu', function (e) { e.preventDefault(); });
        img.addEventListener('dragstart', function (e) { e.preventDefault(); });

        // Block mobile long-press "Save Image" (iOS Safari / Android Chrome)
        var longPressTimer;
        img.addEventListener('touchstart', function (e) {
            longPressTimer = setTimeout(function () { e.preventDefault(); }, 500);
        }, { passive: false });
        img.addEventListener('touchend', function () { clearTimeout(longPressTimer); });
        img.addEventListener('touchmove', function () { clearTimeout(longPressTimer); });
    });

    // Also block right-click on shield overlays
    document.querySelectorAll('.pd-img-shield').forEach(function (shield) {
        shield.addEventListener('contextmenu', function (e) { e.preventDefault(); });
    });

    // Mobile menu toggle
    const mobileMenu = document.getElementById('mobile-menu');
    const navSidebar = document.getElementById('nav-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (mobileMenu && navSidebar && sidebarOverlay) {
        function toggleMenu() {
            mobileMenu.classList.toggle('is-active');
            navSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            
            // Prevent body scrolling when menu is open
            if (navSidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        mobileMenu.addEventListener('click', toggleMenu);
        sidebarOverlay.addEventListener('click', toggleMenu);
    }
});
