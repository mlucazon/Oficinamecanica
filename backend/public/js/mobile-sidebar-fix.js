(function () {
    function isMobile() {
        return window.matchMedia('(max-width: 900px)').matches;
    }

    function ensureMobilePanel() {
        let panel = document.getElementById('mobile-sidebar-panel');
        if (panel) return panel;

        const source = document.querySelector('#sidebar .nav-scroll');
        if (!source) return null;

        panel = document.createElement('aside');
        panel.id = 'mobile-sidebar-panel';
        panel.setAttribute('aria-hidden', 'true');

        const brand = document.createElement('div');
        brand.className = 'mobile-sidebar-brand';
        brand.innerHTML = '<span class="mobile-sidebar-brand-icon"><i class="bi bi-gear-wide-connected"></i></span><span><strong>AutoTech</strong><small>Oficina Pro</small></span>';

        const list = document.createElement('div');
        list.className = 'mobile-sidebar-list';
        list.innerHTML = source.innerHTML;

        panel.appendChild(brand);
        panel.appendChild(list);
        document.body.appendChild(panel);

        list.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobile()) {
                    setOpen(false);
                }
            });
        });

        applyPanelStyles(panel);
        return panel;
    }

    function applyPanelStyles(panel) {
        if (!panel) return;

        Object.assign(panel.style, {
            position: 'fixed',
            top: '0',
            left: '0',
            width: 'min(86vw, 320px)',
            height: '100dvh',
            background: 'linear-gradient(180deg, #02050c, #06080d)',
            color: '#f5f5f5',
            zIndex: '1300',
            transform: document.body.classList.contains('sidebar-open') ? 'translate3d(0,0,0)' : 'translate3d(-105%,0,0)',
            transition: 'transform .24s ease',
            display: 'flex',
            flexDirection: 'column',
            overflow: 'hidden',
            borderRight: '1px solid rgba(255,255,255,.12)',
            boxShadow: '18px 0 48px rgba(0,0,0,.45)'
        });

        const brand = panel.querySelector('.mobile-sidebar-brand');
        if (brand) {
            Object.assign(brand.style, {
                display: 'flex',
                alignItems: 'center',
                gap: '10px',
                minHeight: '58px',
                height: '58px',
                padding: '0 12px',
                borderBottom: '1px solid rgba(255,255,255,.08)',
                boxSizing: 'border-box'
            });
        }

        const icon = panel.querySelector('.mobile-sidebar-brand-icon');
        if (icon) {
            Object.assign(icon.style, {
                width: '38px',
                height: '38px',
                borderRadius: '50%',
                background: '#c40000',
                color: '#fff',
                display: 'inline-flex',
                alignItems: 'center',
                justifyContent: 'center',
                flex: '0 0 38px'
            });
        }

        const strong = panel.querySelector('.mobile-sidebar-brand strong');
        if (strong) {
            Object.assign(strong.style, {
                display: 'block',
                fontSize: '15px',
                lineHeight: '1.1',
                color: '#fff'
            });
        }

        const small = panel.querySelector('.mobile-sidebar-brand small');
        if (small) {
            Object.assign(small.style, {
                display: 'block',
                fontSize: '9px',
                textTransform: 'uppercase',
                letterSpacing: '.16em',
                color: 'rgba(255,255,255,.55)'
            });
        }

        const list = panel.querySelector('.mobile-sidebar-list');
        if (list) {
            Object.assign(list.style, {
                flex: '1 1 auto',
                minHeight: '0',
                overflowY: 'auto',
                overflowX: 'hidden',
                padding: '8px 9px 120px',
                boxSizing: 'border-box',
                WebkitOverflowScrolling: 'touch'
            });
        }

        panel.querySelectorAll('.nav-label').forEach(function (label) {
            Object.assign(label.style, {
                display: 'block',
                opacity: '1',
                color: 'rgba(255,255,255,.58)',
                fontSize: '9px',
                textTransform: 'uppercase',
                letterSpacing: '.14em',
                margin: '8px 0 3px',
                padding: '0 10px',
                transform: 'none',
                maxWidth: '100%',
                height: 'auto'
            });
        });

        panel.querySelectorAll('.nav-link').forEach(function (link) {
            Object.assign(link.style, {
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'flex-start',
                gap: '10px',
                width: '100%',
                minHeight: '38px',
                margin: '1px 0',
                padding: '8px 10px',
                borderRadius: '8px',
                color: '#d8d8d8',
                textDecoration: 'none',
                boxSizing: 'border-box',
                opacity: '1',
                transform: 'none'
            });
        });

        panel.querySelectorAll('.nav-link i').forEach(function (iconEl) {
            Object.assign(iconEl.style, {
                width: '22px',
                minWidth: '22px',
                textAlign: 'center',
                fontSize: '16px',
                opacity: '1',
                color: 'inherit'
            });
        });

        panel.querySelectorAll('.nav-link span').forEach(function (span) {
            Object.assign(span.style, {
                display: 'block',
                flex: '1 1 auto',
                minWidth: '0',
                maxWidth: '100%',
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                whiteSpace: 'nowrap',
                opacity: '1',
                transform: 'none',
                fontSize: '13px',
                letterSpacing: '0',
                color: 'inherit'
            });
        });
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
        const panel = ensureMobilePanel();
        if (!parts.sidebar || !parts.overlay) return;

        parts.sidebar.classList.toggle('open', open);
        parts.overlay.classList.toggle('show', open);
        document.body.classList.toggle('sidebar-open', open);
        parts.sidebar.setAttribute('aria-hidden', open ? 'false' : 'true');
        if (panel) {
            panel.setAttribute('aria-hidden', open ? 'false' : 'true');
            panel.style.transform = open ? 'translate3d(0,0,0)' : 'translate3d(-105%,0,0)';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const parts = getParts();
        if (!parts.sidebar || !parts.overlay || !parts.toggle) return;

        ensureMobilePanel();
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
