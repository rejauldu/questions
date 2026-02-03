// tracker.js
export function initTracker() {
    // Look for a meta tag or a data attribute containing the post ID
    const postElement = document.querySelector('[data-post-id]');
    
    if (postElement) {
        const postId = postElement.getAttribute('data-post-id');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(`/auth/track-activity/${postId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .catch(err => console.debug('Tracking skipped'));
    }
}

// Handler for Section Reordering and Resume Link
function handleHomeSections(data) {
    const container = document.getElementById('dynamic-home-container');
    if (!container) return;

    const bcsSec = document.getElementById('section-bcs');
    const hscSec = document.getElementById('section-hsc');
    const resumeSec = document.getElementById('section-resume');

    // 1. Reorder Logic
    if (bcsSec && hscSec) {
        hscSec.style.order = (data.intent === 'HSC') ? "1" : "2";
        bcsSec.style.order = (data.intent === 'HSC') ? "2" : "1";
    }

    // 2. Resume Logic
    if (data.last_post && resumeSec) {
        resumeSec.classList.remove('hidden');
        const textEl = document.getElementById('resume-text');
        const linkCont = document.getElementById('resume-link-container');
        if (textEl) textEl.innerText = `আপনি সর্বশেষ ${data.last_post.subject_name} পড়ছিলেন।`;
        if (linkCont) {
            linkCont.innerHTML = `
                <a href="${data.last_post.url}" class="px-8 py-3 bg-warning-500 text-white rounded-2xl font-bold block text-center">
                    চালিয়ে যান →
                </a>`;
        }
    }

    // Reveal container
    container.classList.replace('opacity-0', 'opacity-100');
}

// Handler for Hero Content Swap
function handleHeroContent(heroData) {
    const heroContainer = document.getElementById('personalized-hero-content');
    if (!heroContainer || !heroData.html) return;

    heroContainer.classList.add('opacity-0', 'scale-95');
    setTimeout(() => {
        heroContainer.innerHTML = heroData.html;
        heroContainer.classList.remove('opacity-0', 'scale-95');
        heroContainer.classList.add('opacity-100', 'scale-100');
    }, 300);
}

export function loadUserPersonalization() {
    const hasHome = !!document.getElementById('dynamic-home-container');
    const hasHero = !!document.getElementById('personalized-hero-content');

    if (!hasHome && !hasHero) return;

    fetch('/auth/user-intent', {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'success') return;

        // Execute Home Logic if type matches
        if (data.type === 'dynamic_home' || data.intent) {
            handleHomeSections(data);
        }

        // Execute Hero Logic if hero data exists
        if (data.hero) {
            handleHeroContent(data.hero);
        }
    })
    .catch(() => {
        const container = document.getElementById('dynamic-home-container');
        if (container) container.classList.replace('opacity-0', 'opacity-100');
    });
}