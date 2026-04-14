// Filter tags toggle
document.querySelectorAll('.ftag').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.ftag').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    });
});

// Apply buttons
document.querySelectorAll('.btn-apply').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        btn.textContent = '✓ Applied';
        btn.style.background = '#14a800';
        btn.disabled = true;
    });
});