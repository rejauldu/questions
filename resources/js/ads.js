/**
 * Smart AdSense Controller
 * Logic: Wait for X visits AND Y seconds on page, excluding sensitive routes.
 */
export function initAds() {
    const AD_CONFIG = {
        publisherId: 'ca-pub-8895896076224126',
        minVisits: 10,             // Show ads only after the 3rd page view (across sessions)
        timeDelay: 5000,          // Wait 5 seconds after page load before injecting
        excludedPaths: ['/login', '/register', '/read', '/chatbot', '/questions/create', '/auth'],
        visitKey: 'examdao_v_count'
    };

    // 1. Check if the current route is excluded
    const currentPath = window.location.pathname;
    if (AD_CONFIG.excludedPaths.some(path => currentPath.startsWith(path))) {
        return;
    }

    // 2. Update and check Visit Count
    let totalVisits = parseInt(localStorage.getItem(AD_CONFIG.visitKey)) || 0;
    
    // Only increment once per session to prevent refresh spamming from counting as "visits"
    if (!sessionStorage.getItem('session_counted')) {
        totalVisits++;
        localStorage.setItem(AD_CONFIG.visitKey, totalVisits);
        sessionStorage.setItem('session_counted', 'true');
    }

    // 3. Logic: If user is "Loyal" (met visit threshold), inject script after a delay
    if (totalVisits >= AD_CONFIG.minVisits) {
        setTimeout(() => {
            injectAdSenseScript(AD_CONFIG.publisherId);
        }, AD_CONFIG.timeDelay);
    }
}

function injectAdSenseScript(pubId) {
    // Prevent double injection
    if (document.querySelector(`script[src*="${pubId}"]`)) return;

    const script = document.createElement('script');
    script.src = `https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=${pubId}`;
    script.async = true;
    script.crossOrigin = "anonymous";
    
    // Handle script load errors (e.g., ad blockers)
    script.onerror = () => console.log("AdSense failed to load (likely blocked).");
    
    document.head.appendChild(script);
}