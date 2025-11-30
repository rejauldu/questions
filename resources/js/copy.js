// copy.js

document.addEventListener("DOMContentLoaded", function () {
    initCopyButtons();
});

function initCopyButtons() {

    // Grab all copy buttons once
    const copyButtons = document.querySelectorAll(".copy-btn");

    // 🚫 If no copy buttons exist on this page → exit immediately
    if (!copyButtons.length) return;

    // Attach listeners only to existing buttons
    copyButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            const text = this.getAttribute("data-copy");
            if (!text) return;

            // --- Modern Async Clipboard API ---
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => showCopyFeedback(this))
                    .catch(err => console.error("Could not copy text:", err));
            } else {
                // --- Older Browser Fallback ---
                const textarea = document.createElement("textarea");
                textarea.value = text;
                textarea.style.position = "fixed";
                textarea.style.opacity = "0";

                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();

                try {
                    const success = document.execCommand("copy");
                    if (success) {
                        showCopyFeedback(this);
                    } else {
                        console.warn("Fallback copy was unsuccessful.");
                    }
                } catch (err) {
                    console.error("Fallback copy failed:", err);
                } finally {
                    document.body.removeChild(textarea);
                }
            }
        });
    });
}

function showCopyFeedback(button) {
    const originalHtml = button.innerHTML;

    button.classList.add("!text-success-600");

    button.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-success-600">Copied!</span>
    `;

    setTimeout(() => {
        // Only revert if still showing "Copied!"
        if (button.innerHTML.includes("Copied!")) {
            button.classList.remove("!text-success-600");
            button.innerHTML = originalHtml;
        }
    }, 1000);
}