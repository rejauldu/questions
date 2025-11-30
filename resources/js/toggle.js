// toggle.js

document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById('answer-toggle');

    // 🚫 If this page has no toggle button → stop immediately
    if (!toggleButton) return;

    initToggle();
});

function initToggle() {
    const toggleButton = document.getElementById('answer-toggle');
    const answerContent = document.getElementById('answer-content');
    const toggleIcon = document.getElementById('toggle-icon');

    // safety check: avoid errors on pages missing any element
    if (!toggleButton || !answerContent || !toggleIcon) {
        return;
    }

    toggleButton.addEventListener('click', () => {
        const isHidden = answerContent.classList.contains('hidden');

        // toggle content visibility
        answerContent.classList.toggle('hidden');

        // rotate icon
        toggleIcon.classList.toggle('rotate-180');

        // update button text
        toggleButton.querySelector('span').textContent =
            isHidden ? 'Hide Answer & Explanation' : 'Show Answer & Explanation';

        // re-render MathJax only when content becomes visible
        if (isHidden && window.MathJax) {
            MathJax.typesetPromise();
        }
    });
}