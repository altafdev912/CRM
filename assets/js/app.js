/**
 * CRM  – app.js
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar toggle
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar   = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            // Desktop: collapse width; mobile: slide in/out
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                document.getElementById('main-content')?.classList.toggle('expanded');
            }
        });

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    }

    // ── Auto-dismiss alerts after 5 s
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function (el) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert?.close();
        }, 5000);
    });

    // ── Highlight active nav link 
    const currentPath = window.location.pathname;
    document.querySelectorAll('#sidebar .nav-link').forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && currentPath.startsWith(href) && href !== '/crm/dashboard') {
            link.classList.add('active');
        }
    });

    // ── Confirm delete (data-attribute API)
    document.querySelectorAll('[data-confirm-delete]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm(btn.dataset.confirmDelete || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // ── Simple client-side table search
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
});

/**
 * Toggle password visibility.
 * Called from inline onclick in views.
 */
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
