import '../css/app.css';
import './bootstrap';

import './postSearch.js';
import './copy.js';
import './toggle.js';
// Tracker now exports the unified personalization loader
import { initTracker, loadUserPersonalization } from './tracker.js';
import { initCampaignEditor } from './campaignEditor.js';
import { initAds } from './ads.js';
import { initAnswerToggle } from './answerToggle.js';

document.addEventListener('DOMContentLoaded', () => {
    initAds();
    initTracker(); 
    
    // Unified call for Home Reordering, Resume Section, and Hero Content
    loadUserPersonalization(); 

    initAnswerToggle();

    if (document.getElementById('campaign-form')) {
        initCampaignEditor();
    }
});