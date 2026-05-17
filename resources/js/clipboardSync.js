export function initClipboardSync() {
    const btn = document.getElementById('save-clipboard-btn');
    if (!btn) return;

    btn.addEventListener('click', async () => {
        // Get the ID of the post we want to update
        const postId = btn.getAttribute('data-post-id');
        
        if (!postId) {
            console.error("No Post ID found on button.");
            return;
        }

        try {
            // 1. Request text from clipboard
            const text = await navigator.clipboard.readText();
            
            if (!text.trim()) {
                alert("Clipboard is empty!");
                return;
            }

            // 2. Send to your custom Laravel PUT route
            // We use axios.put to match your Route::put definition
            const response = await axios.put('/auth/clipboard/store', {
                content: text,
                post_id: postId
            });

            if (response.data.success) {
                // UI Success Feedback
                const originalText = btn.innerHTML;
                btn.innerHTML = "<b>Saved!</b>"; // Using <b> per your formatting preference
                btn.classList.add('bg-green-500'); // Assuming Tailwind is used
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-green-500');
                }, 2000);
            }
        } catch (err) {
            // Handle "Already contains data" (409) or "Unauthorized" (401)
            const errorMsg = err.response?.data?.message || "Permission denied.";
            alert(errorMsg);
            console.error('Clipboard error:', err);
        }
    });
}