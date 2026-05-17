//app.js
import '../css/app.css';
import './bootstrap';

import './copy.js';
import './toggle.js';
import { initHorizontalScroll } from './horizontalScroll.js';
import { initAppSync } from './tracker.js'; 
import { initMobileMenu } from './menu.js';
import { initCampaignEditor } from './campaignEditor.js';
import { initAds } from './ads.js';
import { initAnswerToggle } from './answerToggle.js';
import { initClipboardSync } from './clipboardSync.js'; 
// 1. Import the Share Module
import { FacebookShare } from './share.js'; 

document.addEventListener('DOMContentLoaded', () => {
    // 1. Core Sync
    initAppSync(); 

    // 2. Navigation
    initMobileMenu(); 

    // 3. Feature initializations
    initAds();
    // Initialize Board/Year Filter
    initHorizontalScroll('board-scroll', 'bg-emerald-600', 'left-arrow', 'right-arrow');
    
    // Initialize Subject Filter
    // Note: Use the ID of your arrows for the subject bar here
    initHorizontalScroll('sub-scroll', 'bg-emerald-600', 'left-arrow', 'right-arrow');

    initAnswerToggle();
    initClipboardSync(); 

    // 4. Initialize Share Buttons (Desktop, Mobile, and Single Page)
    // This will look for the IDs we defined in the HTML
    FacebookShare.init('fb-share-header');

    // 5. Conditional UI
    if (document.getElementById('campaign-form')) {
        initCampaignEditor();
    }
});