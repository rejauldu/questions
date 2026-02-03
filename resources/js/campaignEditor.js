/**
 * Campaign Management Logic for examdao.com
 */

export function initCampaignEditor() {
    // We attach these to window so the inline 'onclick' in Blade can find them
    window.editCampaign = function(id) {
        const form = document.getElementById('campaign-form');
        const title = document.getElementById('form-title');
        const method = document.getElementById('form-method');
        const submitBtn = document.getElementById('submit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const container = document.getElementById('form-container');

        // Show loading state on button if you want, or just fetch
        fetch(`/auth/campaigns/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                // Update Form UI
                title.innerText = "Editing Campaign: " + data.institution_id;
                title.classList.replace('text-primary-600', 'text-blue-600');
                form.action = `/auth/campaigns/${id}`;
                method.value = "PUT";
                submitBtn.innerText = "Update Campaign Data";
                submitBtn.classList.replace('bg-primary-600', 'bg-blue-600');
                cancelBtn.classList.remove('hidden');
                container.classList.add('ring-2', 'ring-blue-100', 'border-blue-300');

                // Fill Inputs
                document.getElementById('inst_id').value = data.institution_id;
                document.getElementById('tagline').value = data.tagline;
                document.getElementById('headline').value = data.headline;
                document.getElementById('btn_text').value = data.button_text;

                // Smooth scroll to form
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(err => alert("Error fetching data."));
    };

    window.resetCampaignForm = function() {
        const form = document.getElementById('campaign-form');
        const title = document.getElementById('form-title');
        const method = document.getElementById('form-method');
        const submitBtn = document.getElementById('submit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const container = document.getElementById('form-container');

        // Reset UI
        title.innerText = "Create New Campaign";
        title.classList.replace('text-blue-600', 'text-primary-600');
        form.action = "/auth/campaigns";
        method.value = "POST";
        submitBtn.innerText = "Save Campaign";
        submitBtn.classList.replace('bg-blue-600', 'bg-primary-600');
        cancelBtn.classList.add('hidden');
        container.classList.remove('ring-2', 'ring-blue-100', 'border-blue-300');

        form.reset();
    };
}