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

const createProjectOpen = document.querySelector('#create-project-open');
const createProjectDialog = document.querySelector('#create-project-dialog');
const createProjectClose = document.querySelector('#create-project-cancel');

if (createProjectOpen instanceof HTMLButtonElement && createProjectDialog instanceof HTMLDialogElement) {
    createProjectOpen.addEventListener('click', () => {
        createProjectDialog.showModal();
    });
}

if (createProjectClose instanceof HTMLButtonElement && createProjectDialog instanceof HTMLDialogElement) {
    createProjectClose.addEventListener('click', () => {
        createProjectDialog.close();
    });
}
