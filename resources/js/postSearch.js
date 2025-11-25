document.addEventListener('DOMContentLoaded', function() {
    const instIdSelect = document.getElementById('institution_id');
    const subjectSelect = document.getElementById('subject');
    const subjectsApiUrl = typeof SUBJECTS_API_URL !== 'undefined' ? SUBJECTS_API_URL : '';
    const currentSubject = typeof CURRENT_SUBJECT !== 'undefined' ? CURRENT_SUBJECT : '';
    const currentInstitutionId = typeof CURRENT_INSTITUTION_ID !== 'undefined' ? CURRENT_INSTITUTION_ID : '';

    const loadSubjects = async (institutionId, selectedSubject = null) => {
        subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';

        if (!institutionId) {
            subjectSelect.innerHTML = '<option value="">Select Institution First</option>';
            return;
        }

        try {
            const response = await fetch(`${subjectsApiUrl}?institution_id=${institutionId}`);
            if (!response.ok) throw new Error('Network response was not ok');

            const subjects = await response.json();

            subjectSelect.innerHTML = '<option value="">All Subjects</option>';

            if (subjects && subjects.length) {
                subjects.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub;
                    option.textContent = sub;
                    if (sub === selectedSubject) option.selected = true;
                    subjectSelect.appendChild(option);
                });
            } else {
                subjectSelect.innerHTML = '<option value="">No subjects found</option>';
            }
        } catch (err) {
            console.error(err);
            subjectSelect.innerHTML = '<option value="">Failed to load subjects</option>';
        }
    };

    // On institution change
    if (instIdSelect) {
        instIdSelect.addEventListener('change', function() {
            const institutionId = this.value;
            loadSubjects(institutionId);
        });
    }

    // Initial page load: if institution is pre-selected, load subjects
    if (currentInstitutionId) {
        loadSubjects(currentInstitutionId, currentSubject);
    }
});