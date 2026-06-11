// Block right-click context menu on ALL images site-wide (document-level catch-all)
document.addEventListener('contextmenu', function(e) {
    if (e.target.tagName === 'IMG') {
        e.preventDefault();
        return false;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Prevent dragging on all images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.setAttribute('draggable', 'false');
        img.addEventListener('contextmenu', e => e.preventDefault());
        img.addEventListener('dragstart', e => e.preventDefault());
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
