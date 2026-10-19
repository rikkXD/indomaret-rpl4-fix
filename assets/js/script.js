// Interaksi kecil yang tidak mengubah alur data atau pengiriman form.
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('#sidebar');
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');
    const closeSidebar = () => {
        if (!sidebar || !sidebarToggle || !sidebarBackdrop) return;
        sidebar.classList.remove('is-open');
        sidebarToggle.setAttribute('aria-expanded', 'false');
        sidebarBackdrop.hidden = true;
    };
    if (sidebar && sidebarToggle && sidebarBackdrop) {
        sidebarToggle.addEventListener('click', () => {
            const isOpen = sidebar.classList.toggle('is-open');
            sidebarToggle.setAttribute('aria-expanded', String(isOpen));
            sidebarBackdrop.hidden = !isOpen;
        });
        sidebarBackdrop.addEventListener('click', closeSidebar);
        window.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeSidebar(); });
    }
    document.querySelectorAll('[data-dismiss-alert]').forEach((button) => {
        button.addEventListener('click', () => button.closest('.alert')?.remove());
    });
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === 'true') return;
            event.preventDefault();
            const modal = document.createElement('div');
            modal.className = 'confirm-modal';
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            modal.setAttribute('aria-labelledby', 'confirm-title');
            modal.innerHTML = '<div class="confirm-card"><h2 id="confirm-title">Konfirmasi penghapusan</h2><p></p><div class="confirm-actions"><button class="button button-secondary" type="button" data-cancel>Batal</button><button class="button button-danger" type="button" data-approve>Hapus</button></div></div>';
            modal.querySelector('p').textContent = form.dataset.confirm || 'Data ini akan dihapus.';
            const closeModal = () => modal.remove();
            modal.querySelector('[data-cancel]').addEventListener('click', closeModal);
            modal.addEventListener('click', (clickEvent) => { if (clickEvent.target === modal) closeModal(); });
            modal.querySelector('[data-approve]').addEventListener('click', () => { form.dataset.confirmed = 'true'; form.requestSubmit(); });
            document.body.append(modal);
            modal.querySelector('[data-cancel]').focus();
        });
    });
});
