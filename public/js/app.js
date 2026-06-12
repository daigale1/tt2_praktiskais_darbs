/* ============================================================
   app.js - Global frontend functionality
   Handles dark mode toggling, poster popup, toast notifications,
   and card fade-out animation.
   ============================================================ */

/* ---------- DARK MODE ---------- */
// Immediately apply saved theme preference on page load
(function () {
    const saved = localStorage.getItem('theme') || 'light';
    applyTheme(saved);
})();

/**
 * Toggles between light and dark mode.
 * Called from the theme toggle button in the sidebar.
 */
function toggleDarkMode() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    localStorage.setItem('theme', next);
}

/**
 * Applies the selected theme by setting data-theme attribute
 * and toggling visibility of sun/moon icons.
 * @param {string} theme - 'light' or 'dark'
 */
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    const sun = document.getElementById('modeIconSun');
    const moon = document.getElementById('modeIconMoon');
    if (!sun || !moon) return;
    if (theme === 'dark') {
        sun.style.display = 'none';
        moon.style.display = '';
    } else {
        sun.style.display = '';
        moon.style.display = 'none';
    }
}

/* ---------- POSTER POPUP (user profile preview) ---------- */
/**
 * Closes the poster popup.
 */
function closePopup() {
    document.getElementById('posterPopup').classList.remove('open');
}

// Close popup when clicking outside of it
document.addEventListener('click', function (e) {
    const popup = document.getElementById('posterPopup');
    if (!popup) return;
    // If click target is not inside popup and not the poster link, close it
    if (!popup.contains(e.target) && !e.target.classList.contains('poster-link')) {
        closePopup();
    }
});

/* ---------- TOAST NOTIFICATION ---------- */
/**
 * Shows a temporary toast message at the bottom center.
 * @param {string} message - Text to display
 * @param {number} duration - Milliseconds to show (default 2500)
 */
function showToast(message, duration = 2500) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), duration);
}

/* ---------- CARD FADE TRANSITION (for feed) ---------- */
/**
 * Adds a fade-out transition to the task card before submitting a form.
 * Used when clicking "View next task" or "Offer to help" to smooth the UX.
 * @param {HTMLFormElement} formEl - The form to submit after animation
 * @returns {boolean} false to prevent immediate submission
 */
function animateCardOut(formEl) {
    const card = document.getElementById('taskCard');
    if (card) {
        card.style.transition = 'opacity 0.2s';
        card.style.opacity = '0';
    }
    setTimeout(() => formEl.submit(), 220);
    return false; // prevent default submit
}