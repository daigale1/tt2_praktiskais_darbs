/* app.js — Neighbors Helping Neighbors */

/* ─── DARK MODE ─── */
(function () {
    const saved = localStorage.getItem('theme') || 'light';
    applyTheme(saved);
})();

function toggleDarkMode() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    localStorage.setItem('theme', next);
}

function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    const sun  = document.getElementById('modeIconSun');
    const moon = document.getElementById('modeIconMoon');
    if (!sun || !moon) return;
    if (theme === 'dark') {
        sun.style.display  = 'none';
        moon.style.display = '';
    } else {
        sun.style.display  = '';
        moon.style.display = 'none';
    }
}

/* ─── POSTER POPUP ─── */
function closePopup() {
    document.getElementById('posterPopup').classList.remove('open');
}

// Close popup when clicking outside of it
document.addEventListener('click', function (e) {
    const popup = document.getElementById('posterPopup');
    if (!popup) return;
    if (!popup.contains(e.target) && !e.target.classList.contains('poster-link')) {
        closePopup();
    }
});

/* ─── TOAST ─── */
function showToast(message, duration = 2500) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), duration);
}

/* ─── CARD FADE TRANSITION ─── */
// Called before form submit to animate the card out
function animateCardOut(formEl) {
    const card = document.getElementById('taskCard');
    if (card) {
        card.style.transition = 'opacity 0.2s';
        card.style.opacity = '0';
    }
    setTimeout(() => formEl.submit(), 220);
    return false; // prevent immediate submit — setTimeout handles it
}
