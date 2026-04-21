// ===== Theme Toggle =====
(function () {
    var html      = document.documentElement;
    var body      = document.body;
    var btnSnow   = document.getElementById('btn-snow');
    var btnCarbon = document.getElementById('btn-carbon');

    var saved = (document.cookie.match(/(?:^|;\s*)theme=([^;]*)/) || [])[1] || 'snow';
    applyTheme(saved);

    function applyTheme(theme) {
        if (theme === 'carbon') {
            html.classList.add('carbon');
            body.classList.add('carbon');
        } else {
            html.classList.remove('carbon');
            body.classList.remove('carbon');
        }
        if (btnSnow)   btnSnow.classList.toggle('active',   theme !== 'carbon');
        if (btnCarbon) btnCarbon.classList.toggle('active', theme === 'carbon');
        document.cookie = 'theme=' + theme + ';path=/;max-age=31536000';
    }

    if (btnSnow)   btnSnow.addEventListener('click',   function () { applyTheme('snow'); });
    if (btnCarbon) btnCarbon.addEventListener('click', function () { applyTheme('carbon'); });
})();

// ===== Password Toggle (profile page) =====
(function () {
    var eyeOpen   = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    var eyeClosed = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>'
                  + '<path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>'
                  + '<line x1="1" y1="1" x2="23" y2="23"/>';

    document.querySelectorAll('.toggle-pw[data-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.getElementById(btn.getAttribute('data-target'));
            var icon   = btn.querySelector('.eye-icon');
            if (!target) return;
            var show = target.type === 'password';
            target.type = show ? 'text' : 'password';
            if (icon) icon.innerHTML = show ? eyeClosed : eyeOpen;
        });
    });
})();

// ===== Sidebar Toggle =====
(function () {
    var sidebar       = document.getElementById('sidebar');
    var toggleBtn     = document.getElementById('sidebarToggleBtn');
    var overlay       = document.getElementById('sidebarOverlay');

    if (!sidebar || !toggleBtn) return;

    var isMobile = function () { return window.innerWidth <= 768; };

    toggleBtn.addEventListener('click', function () {
        if (isMobile()) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
        }
    });

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        });
    }
})();

// ===== Sidebar Section Switching =====
(function () {
    var links    = document.querySelectorAll('.sidebar-link[data-section]');
    var sections = document.querySelectorAll('.section-content');

    // Activate section from URL hash or query param
    var params  = new URLSearchParams(window.location.search);
    var current = params.get('section') || 'users';
    showSection(current);

    links.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var sec = link.getAttribute('data-section');
            showSection(sec);
            // Close mobile sidebar
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.remove('mobile-open');
            if (overlay) overlay.classList.remove('active');
            // Update URL without reload
            var url = new URL(window.location.href);
            url.searchParams.set('section', sec);
            history.pushState({}, '', url.toString());
        });
    });

    function showSection(name) {
        sections.forEach(function (s) { s.classList.remove('active'); });
        links.forEach(function (l) { l.classList.remove('active'); });
        var target = document.getElementById('section-' + name);
        if (target) target.classList.add('active');
        var activeLink = document.querySelector('.sidebar-link[data-section="' + name + '"]');
        if (activeLink) activeLink.classList.add('active');
    }
})();