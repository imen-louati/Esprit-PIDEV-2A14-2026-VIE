(function () {
    function currentValue(form, name) {
        var fields = form.querySelectorAll('[name="' + name + '"]');
        var value = '';
        fields.forEach(function (field) {
            if (field.type === 'radio') {
                if (field.checked) {
                    value = field.value;
                }
            } else if (field.type === 'checkbox') {
                if (field.checked) {
                    value = field.value;
                }
            } else {
                value = field.value;
            }
        });
        return value;
    }

    function applyConditions(form) {
        form.querySelectorAll('[data-visible-if]').forEach(function (block) {
            var rules = block.getAttribute('data-visible-if').split(';');
            var show = rules.every(function (rule) {
                var parts = rule.split('=');
                var allowed = (parts[1] || '').split(',');
                return allowed.indexOf(currentValue(form, parts[0])) !== -1;
            });
            block.hidden = !show;
            block.querySelectorAll('input, select, textarea').forEach(function (field) {
                field.disabled = !show;
            });
        });
    }

    function errorBox(field) {
        var wrap = field.closest('.field') || field.parentNode;
        var box = wrap.querySelector('.js-error');
        if (!box) {
            box = document.createElement('p');
            box.className = 'field-error js-error';
            box.setAttribute('role', 'alert');
            wrap.appendChild(box);
        }
        return box;
    }

    function setError(field, message) {
        var wrap = field.closest('.field');
        var box = errorBox(field);
        if (message) {
            box.textContent = message;
            field.setAttribute('aria-invalid', 'true');
            if (wrap) {
                wrap.classList.add('has-error');
            }
        } else {
            box.textContent = '';
            field.removeAttribute('aria-invalid');
            if (wrap) {
                wrap.classList.remove('has-error');
            }
        }
    }

    function passwordProblem(value) {
        if (value.length < 8) {
            return 'Au moins 8 caractères.';
        }
        if (value.length > 72) {
            return '72 caractères maximum.';
        }
        if (!/[a-z]/.test(value) || !/[A-Z]/.test(value) || !/[0-9]/.test(value)) {
            return 'Une majuscule, une minuscule et un chiffre sont requis.';
        }
        return '';
    }

    function check(field, form) {
        if (field.disabled || field.type === 'hidden' || field.type === 'submit' || field.type === 'button') {
            return '';
        }
        var value = field.type === 'password' ? field.value : field.value.trim();

        if (field.type === 'checkbox') {
            return field.required && !field.checked ? 'Cette case est obligatoire.' : '';
        }
        if (field.required && value === '') {
            return 'Ce champ est obligatoire.';
        }
        if (value === '') {
            return '';
        }
        if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            return 'Adresse e-mail invalide.';
        }
        if (field.minLength > 0 && value.length < field.minLength) {
            return 'Minimum ' + field.minLength + ' caractères.';
        }
        if (field.maxLength > 0 && value.length > field.maxLength) {
            return 'Maximum ' + field.maxLength + ' caractères.';
        }
        if (field.getAttribute('pattern') && !new RegExp('^(?:' + field.getAttribute('pattern') + ')$').test(value)) {
            return field.getAttribute('data-error-pattern') || 'Format invalide.';
        }
        if (field.getAttribute('data-rule') === 'phone' && !/^\+?[0-9][0-9 ]{6,17}[0-9]$/.test(value)) {
            return 'Numéro invalide (8 à 15 chiffres, indicatif + autorisé).';
        }
        if (field.getAttribute('data-rule') === 'password') {
            var problem = passwordProblem(value);
            if (problem) {
                return problem;
            }
        }
        if (field.type === 'date') {
            if (field.min && value < field.min) {
                return 'Date trop ancienne.';
            }
            if (field.max && value > field.max) {
                return 'La date ne peut pas être dans le futur.';
            }
        }
        var match = field.getAttribute('data-match');
        if (match) {
            var other = form.querySelector(match);
            if (other && other.value !== field.value) {
                return 'Les mots de passe ne correspondent pas.';
            }
        }
        return '';
    }

    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        applyConditions(form);

        form.addEventListener('change', function (e) {
            if (e.target.matches('input[type="radio"], select')) {
                applyConditions(form);
            }
        });

        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            field.addEventListener('blur', function () {
                if (field.type !== 'radio') {
                    setError(field, check(field, form));
                }
            });
            field.addEventListener('input', function () {
                if (field.closest('.field') && field.closest('.field').classList.contains('has-error')) {
                    setError(field, check(field, form));
                }
            });
        });

        form.addEventListener('submit', function (e) {
            var firstInvalid = null;
            form.querySelectorAll('input, select, textarea').forEach(function (field) {
                var message = check(field, form);
                setError(field, message);
                if (message && !firstInvalid) {
                    firstInvalid = field;
                }
            });
            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return;
            }
            var message = form.getAttribute('data-confirm');
            if (message && !window.confirm(message)) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector(btn.getAttribute('data-toggle-password'));
            if (!input) {
                return;
            }
            var visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            btn.textContent = visible ? 'Afficher' : 'Masquer';
            btn.setAttribute('aria-label', visible ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
        });
    });

    document.querySelectorAll('[data-strength-for]').forEach(function (meter) {
        var input = document.querySelector(meter.getAttribute('data-strength-for'));
        if (!input) {
            return;
        }
        input.addEventListener('input', function () {
            var v = input.value;
            var score = 0;
            if (v.length >= 8) { score++; }
            if (/[a-z]/.test(v) && /[A-Z]/.test(v)) { score++; }
            if (/[0-9]/.test(v)) { score++; }
            if (/[^A-Za-z0-9]/.test(v) || v.length >= 12) { score++; }
            meter.setAttribute('data-level', v === '' ? '0' : String(Math.max(1, score)));
        });
    });

    document.querySelectorAll('form[data-confirm]:not([data-validate])').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-visible-if]').forEach(function (block) {
        var form = block.closest('form');
        if (form && !form.hasAttribute('data-validate')) {
            applyConditions(form);
        }
    });
})();
