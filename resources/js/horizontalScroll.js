/**
 * Generic horizontal scroll and filter helper
 */
export function initHorizontalScroll(containerId, activeClass, leftArrowId, rightArrowId) {
    const container = document.getElementById(containerId);
    const leftArrow = document.getElementById(leftArrowId);
    const rightArrow = document.getElementById(rightArrowId);
    
    if (!container) return;

    // --- 1. Arrow Click Handlers ---
    const scrollAmount = 300;
    leftArrow?.addEventListener('click', () => container.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    rightArrow?.addEventListener('click', () => container.scrollBy({ left: scrollAmount, behavior: 'smooth' }));

    // --- 2. Enhanced Filter Functionality ---
    const searchInput = container.parentElement.querySelector('input[type="text"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const items = container.querySelectorAll('.filter-item');
            
            items.forEach(item => {
                // Get all three datasets
                const b = item.getAttribute('data-bangla')?.toLowerCase() || '';
                const e = item.getAttribute('data-english')?.toLowerCase() || '';
                const bl = item.getAttribute('data-banglish')?.toLowerCase() || '';
                
                // Check if any of the three fields contain the search term
                const isMatch = b.includes(term) || e.includes(term) || bl.includes(term);
                
                item.style.display = isMatch ? 'block' : 'none';
            });
        });
    }

    // --- 3. Auto-center Active Link ---
    let activeLink = container.querySelector('.' + activeClass);
    if (!activeLink) {
        const checkedRadio = container.querySelector('input:checked + label');
        if (checkedRadio) activeLink = checkedRadio;
    }

    if (activeLink) {
        setTimeout(() => {
            const offset = activeLink.offsetLeft - (container.offsetWidth / 2) + (activeLink.offsetWidth / 2);
            container.scrollTo({ left: offset, behavior: 'smooth' });
        }, 150);
    }
}