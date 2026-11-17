(function () {
    var body = document.body;
    var menuBtn = document.getElementById('menu-toggle');
    var overlay = document.querySelector('.app-overlay');

    if (menuBtn) {
        menuBtn.addEventListener('click', function () {
            body.classList.toggle('nav-open');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', function () {
            body.classList.remove('nav-open');
        });
    }

    var toast = document.createElement('div');
    toast.className = 'toast';
    toast.setAttribute('role', 'status');
    document.body.appendChild(toast);
    var toastTimer = null;

    function notify(message) {
        toast.textContent = message;
        toast.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () {
            toast.classList.remove('is-visible');
        }, 2600);
    }

    document.querySelectorAll('[data-filter-group]').forEach(function (group) {
        var target = document.querySelector(group.getAttribute('data-filter-group'));
        if (!target) {
            return;
        }
        var rows = target.querySelectorAll('[data-role]');
        group.querySelectorAll('[data-filter]').forEach(function (chip) {
            chip.addEventListener('click', function () {
                var value = chip.getAttribute('data-filter');
                group.querySelectorAll('[data-filter]').forEach(function (c) {
                    c.classList.toggle('is-active', c === chip);
                });
                rows.forEach(function (row) {
                    var match = value === 'all' || row.getAttribute('data-role') === value || row.getAttribute('data-status') === value;
                    row.classList.toggle('is-hidden', !match);
                });
            });
        });
    });

    document.querySelectorAll('[data-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var action = btn.getAttribute('data-action');
            var row = btn.closest('[data-row]');
            var name = row ? row.getAttribute('data-name') : '';

            if (action === 'delete' && row) {
                if (window.confirm('Supprimer définitivement ' + (name || 'cet élément') + ' ?')) {
                    row.parentNode.removeChild(row);
                    notify((name || 'Élément') + ' supprimé');
                }
                return;
            }
            if (action === 'validate' && row) {
                var badge = row.querySelector('[data-status-badge]');
                if (badge) {
                    badge.className = 'badge b-green';
                    badge.textContent = 'Actif';
                }
                row.setAttribute('data-status', 'actif');
                btn.parentNode.removeChild(btn);
                notify((name || 'Compte') + ' validé');
                return;
            }
            if (action === 'suspend' && row) {
                var b = row.querySelector('[data-status-badge]');
                var suspended = b && b.classList.contains('b-red');
                if (b) {
                    b.className = suspended ? 'badge b-green' : 'badge b-red';
                    b.textContent = suspended ? 'Actif' : 'Suspendu';
                }
                btn.textContent = suspended ? 'Suspendre' : 'Réactiver';
                notify((name || 'Compte') + (suspended ? ' réactivé' : ' suspendu'));
                return;
            }
            if (action === 'accept' || action === 'refuse') {
                if (row) {
                    row.parentNode.removeChild(row);
                }
                notify(action === 'accept' ? 'Demande acceptée' : 'Demande refusée');
                return;
            }
            if (action === 'copy') {
                var value = btn.getAttribute('data-value') || '';
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(value);
                }
                notify('Code copié : ' + value);
                return;
            }
            if (action === 'notify') {
                notify(btn.getAttribute('data-message') || 'Action enregistrée');
            }
        });
    });

    document.querySelectorAll('.check').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var on = btn.getAttribute('aria-pressed') === 'true';
            btn.setAttribute('aria-pressed', on ? 'false' : 'true');
            notify(on ? 'Prise annulée' : 'Prise enregistrée');
        });
    });

    document.querySelectorAll('.switch input').forEach(function (input) {
        input.addEventListener('change', function () {
            notify(input.checked ? 'Partage activé' : 'Partage désactivé');
        });
    });
})();
