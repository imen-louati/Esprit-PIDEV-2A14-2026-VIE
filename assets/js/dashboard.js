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

    document.querySelectorAll('.alert-close').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var alert = btn.closest('.alert');
            if (alert) {
                alert.parentNode.removeChild(alert);
            }
        });
    });

    var toast = document.createElement('div');
    toast.className = 'toast';
    toast.setAttribute('role', 'status');
    document.body.appendChild(toast);
    var timer = null;

    function notify(message) {
        toast.textContent = message;
        toast.classList.add('is-visible');
        clearTimeout(timer);
        timer = setTimeout(function () {
            toast.classList.remove('is-visible');
        }, 2400);
    }

    document.querySelectorAll('[data-action="copy"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var value = btn.getAttribute('data-value') || '';
            if (navigator.clipboard) {
                navigator.clipboard.writeText(value).then(function () {
                    notify('Code copié : ' + value);
                }, function () {
                    notify('Copie impossible, sélectionnez le code manuellement.');
                });
            } else {
                notify('Copie impossible, sélectionnez le code manuellement.');
            }
        });
    });
})();
