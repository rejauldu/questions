document.addEventListener('DOMContentLoaded', function() {
    const instIdSelect = document.getElementById('institution_id');
    const subjectSelect = document.getElementById('subject_id');

    // Skip entirely if the page doesn't have these elements
    if (!instIdSelect || !subjectSelect) return;

    const subjectsApiUrl = typeof SUBJECTS_API_URL !== 'undefined' ? SUBJECTS_API_URL : '';
    const currentSubject = typeof CURRENT_SUBJECT !== 'undefined' ? CURRENT_SUBJECT : '';
    const currentInstitutionId = typeof CURRENT_INSTITUTION_ID !== 'undefined' ? CURRENT_INSTITUTION_ID : '';

    // Fetch and populate subjects
    const loadSubjects = async (institutionId, selectedSubject = null) => {
        subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
        if (!institutionId) {
            subjectSelect.innerHTML = '<option value="">Select Institution First</option>';
            return;
        }

        try {
            const res = await fetch(`${subjectsApiUrl}?institution_id=${institutionId}`);
            if (!res.ok) throw new Error('Network response was not ok');

            const subjects = await res.json();
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';

            if (subjects?.length) {
                subjects.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.name;
                    if (sub.id === +selectedSubject) option.selected = true;
                    subjectSelect.appendChild(option);
                });
            } else {
                subjectSelect.innerHTML = '<option value="">No subjects found</option>';
            }
        } catch (err) {
            console.error('Failed to load subjects:', err);
            subjectSelect.innerHTML = '<option value="">Failed to load subjects</option>';
        }
    };

    // Bind change event only once
    instIdSelect.addEventListener('change', function() {
        loadSubjects(this.value);
    });

    // Load subjects initially if institution pre-selected
    if (currentInstitutionId) {
        loadSubjects(currentInstitutionId, currentSubject);
    }
});