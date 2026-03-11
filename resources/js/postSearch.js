document.addEventListener('DOMContentLoaded', () => {
    // --- 1. DOM Element Cache ---
    const el = {
        instId: document.getElementById('institution_id'),
        subId: document.getElementById('subject_id'),
        menuToggle: document.getElementById('menu-toggle'),
        mobileMenu: document.getElementById('mobile-menu'),
        dtWrapper: document.getElementById('auth-wrapper-desktop'),
        mbWrapper: document.getElementById('auth-wrapper-mobile'),
        profileItems: document.querySelectorAll('#nav-profile-item, #mobile-profile-item'),
        dashboardItems: document.querySelectorAll('#nav-dashboard-item, #mobile-dashboard-item'),
        logoutForm: document.getElementById('logout-form-dynamic')
    };

    // --- 2. Dependent Dropdown Logic (Untouched) ---
    if (el.instId && el.subId) {
        const config = {
            apiUrl: window.SUBJECTS_API_URL || '',
            currSub: window.CURRENT_SUBJECT || '',
            currInst: window.CURRENT_INSTITUTION_ID || ''
        };
        const loadSubjects = async (instId, selSub = null) => {
            el.subId.disabled = true;
            el.subId.innerHTML = `<option value="">${instId ? 'Loading...' : 'Select Institution First'}</option>`;
            if (!instId) return;
            try {
                const res = await fetch(`${config.apiUrl}?institution_id=${instId}`);
                const subjects = res.ok ? await res.json() : [];
                el.subId.innerHTML = '<option value="">All Subjects</option>';
                subjects.forEach(s => {
                    const opt = new Option(s.name, s.id);
                    if (s.id == selSub) opt.selected = true;
                    el.subId.add(opt);
                });
            } catch (e) { console.error('Subject fetch error:', e); } 
            finally { el.subId.disabled = false; }
        };
        el.instId.addEventListener('change', e => loadSubjects(e.target.value));
        if (config.currInst) loadSubjects(config.currInst, config.currSub);
    }

    // --- 3. Mobile Menu Toggle (Untouched) ---
    if (el.menuToggle && el.mobileMenu) {
        const toggleMenu = (forceClose = false) => {
            const isOpen = el.mobileMenu.classList.contains('max-h-screen');
            if (isOpen || forceClose) {
                el.mobileMenu.classList.replace('max-h-screen', 'max-h-0');
                el.mobileMenu.classList.replace('opacity-100', 'opacity-0');
            } else {
                el.mobileMenu.classList.replace('max-h-0', 'max-h-screen');
                el.mobileMenu.classList.replace('opacity-100', 'opacity-100'); // Ensure visibility
                el.mobileMenu.classList.add('max-h-screen', 'opacity-100');
            }
        };
        el.menuToggle.addEventListener('click', e => { e.stopPropagation(); toggleMenu(); });
        window.addEventListener('click', e => { if (!el.mobileMenu.contains(e.target) && !el.menuToggle.contains(e.target)) toggleMenu(true); });
    }

    // --- 4. Unified Auth & CSRF Sync ---
    const authUrl = "/auth/status"; 
    const loginUrl = "/login";

    fetch(authUrl)
        .then(res => res.json())
        .then(data => {
            // Update CSRF tokens if provided
            if (data.csrf) {
                document.querySelectorAll('input[name="_token"]').forEach(i => i.value = data.csrf);
            }

            const isAuth = !!data.auth;
            const isAdmin = isAuth && data.user && data.user.role === 'admin'; 

            // Handle Profile/Dashboard Visibility
            el.profileItems.forEach(i => i.classList.toggle('hidden', !isAuth));
            el.dashboardItems.forEach(i => i.classList.toggle('hidden', !isAdmin));

            // ONLY update the wrapper if the user IS authenticated
            // If not authenticated, we leave the hardcoded Login button alone
            if (isAuth) {
                const logoutBtnDesktop = `<button onclick="document.getElementById('logout-form-dynamic').submit()" class="bg-red-500 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-red-400 transition shadow-md">Logout</button>`;
                const logoutBtnMobile = `<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-dynamic').submit();" class="block p-3 rounded-xl text-red-400 font-bold hover:bg-white/10 text-center border border-red-400/20">Logout (${data.user.name})</a>`;
                
                if (el.dtWrapper) el.dtWrapper.innerHTML = logoutBtnDesktop;
                if (el.mbWrapper) el.mbWrapper.innerHTML = logoutBtnMobile;
            }
        })
        .catch(err => console.error('Auth sync error:', err));
});