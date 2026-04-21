/* Digitera — client_home.js */

// ── Navbar scroll effect ──
(function () {
    var nav = document.getElementById('clientNav');
    if (!nav) return;
    window.addEventListener('scroll', function () {
        nav.classList.toggle('scrolled', window.scrollY > 50);
    }, { passive: true });
})();

// ── Hamburger ──
(function () {
    var burger = document.getElementById('hamburger');
    var menu   = document.getElementById('navLinksMobile');
    if (!burger || !menu) return;
    burger.addEventListener('click', function () {
        burger.classList.toggle('active');
        menu.classList.toggle('active');
    });
    menu.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
            burger.classList.remove('active');
            menu.classList.remove('active');
        });
    });
    window.addEventListener('scroll', function () {
        burger.classList.remove('active');
        menu.classList.remove('active');
    }, { passive: true });
})();

// ── Mini charts on stat cards ──
function drawMiniChart(canvasId, color) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    canvas.width  = canvas.parentElement.offsetWidth || 240;
    canvas.height = 60;
    var pts = [];
    for (var i = 0; i < 10; i++) pts.push(8 + Math.random() * 44);

    ctx.beginPath();
    ctx.strokeStyle = color;
    ctx.lineWidth = 2;
    pts.forEach(function (y, i) {
        var x = (canvas.width / (pts.length - 1)) * i;
        i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
    });
    ctx.stroke();

    var grad = ctx.createLinearGradient(0, 0, 0, 60);
    grad.addColorStop(0, color + '50');
    grad.addColorStop(1, color + '00');
    ctx.lineTo(canvas.width, 60);
    ctx.lineTo(0, 60);
    ctx.closePath();
    ctx.fillStyle = grad;
    ctx.fill();
}

setTimeout(function () {
    drawMiniChart('sc1', '#00ffcc');
    drawMiniChart('sc2', '#ff0080');
    drawMiniChart('sc3', '#00ccff');
    drawMiniChart('sc4', '#ffcc00');
    drawMiniChart('sc5', '#ff6b6b');
    drawMiniChart('sc6', '#4ecdc4');
}, 150);

// ── Filter tags ──
document.querySelectorAll('.ftag').forEach(function (tag) {
    tag.addEventListener('click', function () {
        document.querySelectorAll('.ftag').forEach(function (t) { t.classList.remove('active'); });
        tag.classList.add('active');
    });
});

// ── IntersectionObserver: metrics & info cards ──
(function () {
    var items = document.querySelectorAll('.ch-metric-item, .info-card');
    if (!window.IntersectionObserver) {
        items.forEach(function (el) { el.classList.add('visible'); });
        return;
    }
    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry, idx) {
            if (entry.isIntersecting) {
                var el = entry.target;
                setTimeout(function () { el.classList.add('visible'); }, idx * 80);
                obs.unobserve(el);
            }
        });
    }, { threshold: 0.2 });
    items.forEach(function (el) { obs.observe(el); });
})();

// ── Navbar active link on scroll ──
(function () {
    var sections = document.querySelectorAll('section[id]');
    var links    = document.querySelectorAll('.nav-links a, .nav-links-mobile a');
    window.addEventListener('scroll', function () {
        var scrollY = window.pageYOffset;
        sections.forEach(function (sec) {
            var top    = sec.offsetTop - 80;
            var bottom = top + sec.offsetHeight;
            if (scrollY >= top && scrollY < bottom) {
                var id = sec.getAttribute('id');
                links.forEach(function (a) {
                    a.classList.remove('active');
                    if (a.getAttribute('href') === '#' + id) a.classList.add('active');
                });
            }
        });
    }, { passive: true });
})();

// ── Smooth scroll ──
document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
});

// ── Contact form ──
(function () {
    var form = document.getElementById('contactForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var btn = form.querySelector('.btn-submit');
        var orig = btn.textContent;
        btn.textContent = 'Message Sent! ✓';
        btn.style.background = 'linear-gradient(135deg,#4ade80,#22c55e)';
        form.reset();
        setTimeout(function () {
            btn.textContent = orig;
            btn.style.background = '';
        }, 3000);
    });
})();