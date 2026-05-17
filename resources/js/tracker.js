// resources/js/tracker.js

const AUTH_KEY = 'app_full_state';

/**
 * Main entry point called by app.js
 * Synchronizes Auth, Tracking, and Personalization in one request.
 */
export function initAppSync() {
    const el = {
        dtWrapper: document.getElementById('auth-wrapper-desktop'),
        mbWrapper: document.getElementById('auth-wrapper-mobile'),
        profileItems: document.querySelectorAll('#nav-profile-item, #mobile-profile-item'),
        dashboardItems: document.querySelectorAll('#nav-dashboard-item, #mobile-dashboard-item'),
        adminActions: document.querySelectorAll('.admin-actions'),
        homeContainer: document.getElementById('dynamic-home-container'),
        heroContainer: document.getElementById('personalized-hero-content')
    };

    // 1. Immediate UI Update from Cache (Prevents Flicker)
    const cachedData = localStorage.getItem(AUTH_KEY);
    if (cachedData) {
        try {
            const parsed = JSON.parse(cachedData);
            // We apply everything except the CSRF from cache to avoid stale token injection
            const cleanCachedData = { ...parsed };
            delete cleanCachedData.csrf; 
            applyAllUpdates(cleanCachedData, el);
        } catch (e) { 
            localStorage.removeItem(AUTH_KEY); 
        }
    }

    // 2. Background Revalidation (Fresh Data via GET to avoid CSRF mismatch)
    const postEl = document.querySelector('[data-post-id]');
    const postId = postEl ? postEl.getAttribute('data-post-id') : null;
    
    // Construct URL with query params
    let url = '/auth/init';
    if (postId) {
        url += `?post_id=${encodeURIComponent(postId)}`;
    }

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.ok ? res.json() : Promise.reject())
    .then(data => {
        // Apply fresh data including the new CSRF token
        applyAllUpdates(data, el);

        // Self-healing: If server says logged out, clear local cache
        if (data.auth === false) {
            localStorage.removeItem(AUTH_KEY);
        } else {
            localStorage.setItem(AUTH_KEY, JSON.stringify(data));
        }
    })
    .catch(err => console.debug('App Sync skipped:', err));
}

/**
 * Master function to update the DOM
 */
function applyAllUpdates(data, el) {
    if (!data) return;

    // A. Update Auth UI & Inject Fresh CSRF
    updateAuthUI(data, el);
    
    // B. Update Home Sections (Intent & Resume)
    if (el.homeContainer && (data.intent || data.last_post)) {
        updateHomeSections(data, el);
    }

    // C. Update Hero Content
    if (el.heroContainer && data.hero?.html) {
        el.heroContainer.innerHTML = data.hero.html;
        el.heroContainer.classList.remove('opacity-0');
    }
}

/**
 * Sub-handler for Login/Logout UI
 */
function updateAuthUI(data, el) {
    const { auth: isAuth, user, csrf } = data;
    const isAdmin = isAuth && user?.role === 'admin';

    // Update CSRF tokens for all forms and meta tags to prevent 419 errors
    if (csrf) {
        // Update hidden inputs in forms (like logout-form)
        document.querySelectorAll('input[name="_token"]').forEach(i => i.value = csrf);
        
        // Update the meta tag for any future JS-based POST requests
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) metaTag.setAttribute('content', csrf);
    }

    // Toggle visibility of protected items
    el.profileItems.forEach(i => i.classList.toggle('hidden', !isAuth));
    el.dashboardItems.forEach(i => i.classList.toggle('hidden', !isAdmin));
    el.adminActions.forEach(i => i.classList.toggle('hidden', !isAdmin));

    if (isAuth && user) {
        // Wipe local cache on click to ensure the next page load re-validates
        const logoutTrigger = `localStorage.removeItem('${AUTH_KEY}'); document.getElementById('logout-form-dynamic').submit();`;
        
        const btnClass = "bg-red-500 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-red-400 transition shadow-md";
        const linkClass = "block p-3 rounded-xl text-red-400 font-bold hover:bg-white/10 text-center border border-red-400/20";
        
        if (el.dtWrapper) {
            el.dtWrapper.innerHTML = `<button onclick="${logoutTrigger}" class="${btnClass}">Logout</button>`;
        }
        if (el.mbWrapper) {
            el.mbWrapper.innerHTML = `<a href="#" onclick="event.preventDefault(); ${logoutTrigger}" class="${linkClass}">Logout (${user.name})</a>`;
        }
    } else {
        // Fallback to Login
        const loginUrl = "/login";
        const dBtn = "bg-yellow-400 text-indigo-900 px-6 py-2 rounded-full text-sm font-bold hover:bg-yellow-300 transition block text-center shadow-md";
        const mBtn = "bg-yellow-400 text-indigo-900 p-3 rounded-xl font-bold hover:bg-yellow-300 transition block text-center shadow-lg";

        if (el.dtWrapper) el.dtWrapper.innerHTML = `<a href="${loginUrl}" class="${dBtn}">Login</a>`;
        if (el.mbWrapper) el.mbWrapper.innerHTML = `<a href="${loginUrl}" class="${mBtn}">Login</a>`;
    }
}

/**
 * Sub-handler for Homepage Personalization
 */
function updateHomeSections(data, el) {
    const hscElements = document.querySelectorAll('.hsc');
    const bcsElements = document.querySelectorAll('.bcs');

    if (data.intent === 'HSC') {
        hscElements.forEach(i => i.classList.remove('hidden'));
        bcsElements.forEach(i => i.classList.add('hidden'));
    } else if (data.intent === 'BCS') {
        bcsElements.forEach(i => i.classList.remove('hidden'));
        hscElements.forEach(i => i.classList.add('hidden'));
    }

    const resumeSec = document.getElementById('section-resume');
    if (data.last_post && resumeSec) {
        resumeSec.classList.remove('hidden');
        const textEl = document.getElementById('resume-text');
        const linkCont = document.getElementById('resume-link-container');
        if (textEl) textEl.innerHTML = `আপনি সর্বশেষ <b>${data.last_post.subject_name}</b> পড়ছিলেন।`;
        if (linkCont) {
            linkCont.innerHTML = `<a href="${data.last_post.url}" class="px-8 py-3 bg-warning-500 text-white rounded-2xl font-bold block text-center">চালিয়ে যান →</a>`;
        }
    }
    
    if (el.homeContainer) {
        el.homeContainer.classList.replace('opacity-0', 'opacity-100');
    }
}