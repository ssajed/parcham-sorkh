
/**
 * Paracham Sorkh - Admin Panel
 *
 * Stage 1: Visual UI
 * - Mobile sidebar interactions
 * - Sidebar overlay and close button
 * - Accessible expanded state
 * - Background scroll locking
 */

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('admin-sidebar');
    const toggleButton = document.querySelector('[data-sidebar-toggle]');
    const closeButton = document.querySelector('[data-sidebar-close]');
    const overlay = document.querySelector('[data-sidebar-overlay]');

    if (!sidebar || !toggleButton || !closeButton || !overlay) {
        return;
    }

    const body = document.body;

    const isMobile = () => window.matchMedia('(max-width: 760px)').matches;

    const openSidebar = () => {
        if (!isMobile()) {
            return;
        }

        toggleButton.setAttribute('aria-expanded', 'true');
        body.classList.add('admin-sidebar-open');
    };

    const closeSidebar = () => {
        toggleButton.setAttribute('aria-expanded', 'false');
        body.classList.remove('admin-sidebar-open');
    };

    const toggleSidebar = () => {
        const isExpanded =
            toggleButton.getAttribute('aria-expanded') === 'true';

        if (isExpanded) {
            closeSidebar();
        } else {
            openSidebar();
        }
    };

    // Open and close with the mobile menu button.
    toggleButton.addEventListener('click', toggleSidebar);

    // Close with the sidebar close button.
    closeButton.addEventListener('click', closeSidebar);

    // Close when the overlay is clicked.
    overlay.addEventListener('click', closeSidebar);

    // Close with Escape.
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    // Close the mobile sidebar when switching to desktop.
    window.matchMedia('(min-width: 761px)').addEventListener('change', (event) => {
        if (event.matches) {
            closeSidebar();
        }
    });

    // Keep the initial state synchronized.
    closeSidebar();
});
