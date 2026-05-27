(function () {
    function isMobile() {
        return window.matchMedia('(max-width: 900px)').matches;
    }

    function getParts() {
        return {
            sidebar: document.getElementById('sidebar'),
            overlay: document.getElementById('sidebar-overlay'),
            toggle: document.getElementById('sidebar-toggle') || document.querySelector('.sidebar-toggle')
        };
    }

    function setOpen(open) {
        const parts = getParts();
        if (!parts.sidebar || !parts.overlay) return;

        parts.sidebar.classList.toggle('open', open);
        parts.overlay.classList.toggle('show', open);
        document.body.classList.toggle('sidebar-open', open);
        parts.sidebar.setAttribute('aria-hidden', open ? 'false' : 'true');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const parts = getParts();
        if (!parts.sidebar || !parts.overlay || !parts.toggle) return;

        parts.sidebar.setAttribute('aria-hidden', 'true');
        parts.toggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            setOpen(!parts.sidebar.classList.contains('open'));
        });

        parts.overlay.addEventListener('click', function () {
            setOpen(false);
        });

        document.querySelectorAll('#sidebar .nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobile()) {
                    setOpen(false);
                }
            });
        });

        window.addEventListener('resize', function () {
            if (!isMobile()) {
                setOpen(false);
            }
        });
    });
})();
