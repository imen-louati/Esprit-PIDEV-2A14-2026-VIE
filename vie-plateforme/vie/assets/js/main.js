(function () {
    var toggleBtn = document.querySelector('.nav-toggle');
    var links = document.getElementById('nav-links');
    if (toggleBtn && links) {
        toggleBtn.addEventListener('click', function () {
            var open = links.classList.toggle('is-open');
            toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        links.addEventListener('click', function (e) {
            if (e.target.tagName === 'A') {
                links.classList.remove('is-open');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    var card = document.querySelector('[data-auth]');
    if (card) {
        var toggle = card.querySelector('.toggle');
        var buttons = toggle.querySelectorAll('button');
        var title = card.querySelector('[data-title]');
        var sub = card.querySelector('[data-sub]');
        var account = card.querySelector('input[name="account"]');
        var email = card.querySelector('input[name="email"]');
        var copy = {
            personne: {
                title: 'Content de vous revoir',
                sub: 'Accédez à votre suivi, vos rendez-vous et vos documents.',
                placeholder: 'nom@exemple.com'
            },
            medecin: {
                title: 'Espace professionnel',
                sub: 'Gérez vos disponibilités, vos patients et vos notes de suivi.',
                placeholder: 'dr.nom@clinique.tn'
            }
        };
        function setMode(mode) {
            toggle.setAttribute('data-active', mode);
            card.classList.toggle('is-medecin', mode === 'medecin');
            buttons.forEach(function (b) {
                var on = b.getAttribute('data-mode') === mode;
                b.classList.toggle('is-active', on);
                b.setAttribute('aria-pressed', on ? 'true' : 'false');
            });
            account.value = mode;
            title.textContent = copy[mode].title;
            sub.textContent = copy[mode].sub;
            email.placeholder = copy[mode].placeholder;
        }
        buttons.forEach(function (b) {
            b.addEventListener('click', function () {
                setMode(b.getAttribute('data-mode'));
            });
        });
        var adminLink = card.querySelector('[data-admin]');
        if (adminLink) {
            adminLink.addEventListener('click', function (e) {
                e.preventDefault();
                account.value = 'admin';
                title.textContent = 'Administration';
                sub.textContent = 'Connexion réservée à l\u2019équipe VIE.';
                email.placeholder = 'admin@vie.tn';
                toggle.setAttribute('data-active', 'personne');
                card.classList.remove('is-medecin');
                buttons.forEach(function (b) {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-pressed', 'false');
                });
            });
        }
        var initial = card.getAttribute('data-initial') || 'personne';
        setMode(initial === 'medecin' ? 'medecin' : 'personne');
    }
})();
