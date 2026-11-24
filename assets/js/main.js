(function () {
    var toggleBtn = document.querySelector('.nav-toggle');
    var links = document.getElementById('nav-links');
    if (!toggleBtn || !links) {
        return;
    }
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
})();
