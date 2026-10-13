const sidebarToggle = document.querySelector('.sidebar-toggle');
const sidebar = document.querySelector('#sidebar');

if (sidebarToggle instanceof HTMLButtonElement && sidebar instanceof HTMLElement) {
    sidebarToggle.addEventListener('click', () => {
        const isExpanded = sidebarToggle.getAttribute('aria-expanded') === 'true';
        sidebarToggle.setAttribute('aria-expanded', String(!isExpanded));
        sidebarToggle.setAttribute('aria-label', isExpanded ? 'Open sidebar' : 'Close sidebar');
        sidebar.inert = isExpanded;
        document.body.classList.toggle('sidebar-collapsed', isExpanded);
    });
}
