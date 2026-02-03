export function initAnswerToggle() {
    const toggle = document.getElementById('answerToggle');
    
    // Safety check: if the toggle isn't on the page, don't run the script
    if (!toggle) return;

    const correctLabels = document.querySelectorAll('.correct-label');
    const correctOptions = document.querySelectorAll('.correct-option');

    toggle.addEventListener('change', function() {
        const active = this.checked;

        // Toggle the label (the 'ক' part)
        correctLabels.forEach(lbl => {
            if (active) {
                lbl.classList.add('text-indigo-600');
                lbl.classList.remove('text-slate-400');
            } else {
                lbl.classList.remove('text-indigo-600');
                lbl.classList.add('text-slate-400');
            }
        });

        // Toggle the parent container (the text + boldness)
        correctOptions.forEach(opt => {
            if (active) {
                opt.classList.add('text-indigo-700', 'font-bold', 'not-italic');
                opt.classList.remove('text-slate-800');
            } else {
                opt.classList.remove('text-indigo-700', 'font-bold', 'not-italic');
                opt.classList.add('text-slate-800');
            }
        });
    });
}