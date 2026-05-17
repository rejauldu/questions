@extends('layout')

@section('content')
@php
    // Collection mapping for status tracking
    $urls = $urls ?? collect([]);
    $warmedCount = $urls->where('is_warmed', true)->count();
    $totalCount = $urls->count();
    $percent = $totalCount > 0 ? round(($warmedCount / $totalCount) * 100) : 0;
@endphp

<div class="p-4 md:p-8 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Maintenance Dashboard</h1>
                <p class="text-slate-500 text-sm">System scripts and Cloudflare edge management.</p>
            </div>
            
            <!-- Global Status Badge -->
            <div id="globalProgress" class="flex items-center gap-4 bg-white p-3 rounded-lg border border-slate-200 shadow-sm transition-all">
                <div class="text-xs font-bold text-blue-600 uppercase tracking-tighter">Server Cache: <span id="statusPercent">{{ $percent }}%</span></div>
                <div class="w-32 bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div id="globalProgressBar" class="bg-blue-600 h-full transition-all duration-300" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg text-sm font-bold">
                {{ session('status') }}
            </div>
        @endif

        <!-- Section 1: Advanced Cache Warmer -->
        <div class="mb-8">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Cloudflare Edge Warmer</h2>
            <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-end gap-2 mb-2">
                            <span id="currentCount" class="text-4xl font-black text-slate-900">{{ $warmedCount }}</span>
                            <span class="text-slate-300 text-xl font-bold mb-1">/ {{ $totalCount }} Pages</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden shadow-inner">
                            <div id="mainProgressBar" class="bg-blue-600 h-full transition-all duration-300 ease-out" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button id="startWarmer" @if($totalCount === 0) disabled @endif class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-md active:scale-95 disabled:opacity-50">
                            <span id="btnText">Start Warming Process</span>
                        </button>
                        <a href="{{ route('admin.cache.index') }}" onclick="return confirm('Purge Cloudflare Edge & Reset Tracking?')" class="inline-flex items-center justify-center px-4 py-3 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold rounded-lg border border-red-100 transition-all">
                            Purge Everything
                        </a>
                    </div>
                </div>

                <!-- Activity Monitoring Bar -->
                <div id="activityBox" class="bg-slate-900 px-6 py-4 flex items-center min-h-[70px]">
                    <div id="idleState" class="text-slate-500 font-mono text-xs italic w-full text-center">>> System Idle. Awaiting start command...</div>
                    
                    <div id="activeState" class="hidden w-full flex items-center justify-between gap-4">
                        <div class="flex-1 truncate">
                            <div class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-0.5">Processing Path:</div>
                            <div id="currentUrlDisplay" class="text-sm font-mono text-green-400 truncate">---</div>
                        </div>
                        <div class="flex gap-4 shrink-0">
                            <div class="text-right">
                                <div class="text-[10px] text-slate-500 uppercase font-bold">Attempt</div>
                                <div id="attemptBadge" class="text-xs font-mono text-white">#1</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] text-slate-500 uppercase font-bold">Retries</div>
                                <div id="retryCount" class="text-xs font-mono text-orange-400">0</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] text-slate-500 uppercase font-bold">Elapsed</div>
                                <div id="timer" class="text-xs font-mono text-white">00:00</div>
                            </div>
                        </div>
                    </div>

                    <div id="completeState" class="hidden w-full text-center">
                        <div class="text-green-400 font-mono text-xs font-bold flex items-center justify-center gap-2">
                            [SUCCESS] Warming Complete. All pages synchronized with edge cache.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: AJAX Maintenance Grid -->
        <div class="mb-8">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Maintenance & Correction Scripts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $ajaxTools = [
                        ['name' => 'Article Formatting', 'desc' => 'Fixes i./ii. breaks & removes <br> tags.', 'url' => '/auth/fix-article-formatting'],
                        ['name' => 'Normalize Hashes', 'desc' => 'Updates question maintenance hashes.', 'url' => '/auth/fix-hash'],
                        ['name' => 'Fix LaTeX Wrapper', 'desc' => 'Wraps 1/x and x^2 in $ symbols.', 'url' => '/auth/fix-latex-wrapper'],
                        ['name' => 'Fix LaTeX (Legacy)', 'desc' => 'Runs old correction script.', 'url' => '/auth/fix-latex'],
                        ['name' => 'Fix Pre Tags', 'desc' => 'Corrects preformatted text blocks.', 'url' => '/auth/fix-pre'],
                        ['name' => 'Fix SVG Tags', 'desc' => 'Corrects SVG syntax in questions.', 'url' => '/auth/fix-svg'],
                        ['name' => 'Fix Tables', 'desc' => 'Repair HTML table structures.', 'url' => '/auth/fix-table'],
                        ['name' => 'Auto Topic', 'desc' => 'Auto populates missing topic IDs.', 'url' => '/auth/auto-topic'],
                        ['name' => 'Remove BR Tags', 'desc' => 'Replaces <br> with newlines.', 'url' => '/auth/remove-br'],
                        ['name' => 'Generate Sitemap', 'desc' => 'Rebuilds public XML sitemap.', 'url' => '/auth/sitemap'],
                        ['name' => 'Remove Q No', 'desc' => 'Removes Question number from start', 'url' => '/auth/remove-q-no'],
                    ];
                @endphp

                @foreach($ajaxTools as $tool)
                <div class="bg-white p-4 rounded-lg border border-slate-200 flex justify-between items-center hover:border-blue-300 transition-all group">
                    <div class="pr-4">
                        <h4 class="font-bold text-slate-700 text-sm">{{ $tool['name'] }}</h4>
                        <p class="text-[11px] text-slate-400 leading-tight">{{ $tool['desc'] }}</p>
                    </div>
                    <a href="{{ $tool['url'] }}" class="ajax-link p-2 bg-slate-50 hover:bg-blue-600 hover:text-white text-blue-600 rounded-md transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Console / Execution Log -->
        <div class="bg-slate-900 rounded-xl shadow-2xl border border-slate-800 overflow-hidden">
            <div class="px-4 py-2 bg-slate-800 border-b border-slate-700 flex justify-between items-center">
                <span class="text-[10px] font-mono text-slate-500 uppercase tracking-widest">Execution Log</span>
                <button id="clearLog" class="text-[10px] text-slate-500 hover:text-white transition-colors">CLEAR</button>
            </div>
            <div id="logContent" class="p-4 h-64 overflow-y-auto font-mono text-[11px] text-green-400 space-y-1 scrollbar-hide">
                <div class="text-slate-600 italic">>> System Ready. {{ $warmedCount }} URLs marked as cached.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data from controller (array of {url: string, is_warmed: bool})
const urlData = @json($urls);

const currentCountEl = document.getElementById('currentCount');
const mainProgressBar = document.getElementById('mainProgressBar');
const globalProgressBar = document.getElementById('globalProgressBar');
const statusPercentEl = document.getElementById('statusPercent');
const startBtn = document.getElementById('startWarmer');
const currentUrlDisplay = document.getElementById('currentUrlDisplay');
const attemptBadge = document.getElementById('attemptBadge');
const retryCountEl = document.getElementById('retryCount');
const timerEl = document.getElementById('timer');

const idleState = document.getElementById('idleState');
const activeState = document.getElementById('activeState');
const completeState = document.getElementById('completeState');

let retries = 0;
let startTime;
let timerInterval;

function updateTimer() {
    const now = new Date();
    const diff = Math.floor((now - startTime) / 1000);
    const mins = Math.floor(diff / 60).toString().padStart(2, '0');
    const secs = (diff % 60).toString().padStart(2, '0');
    timerEl.innerText = `${mins}:${secs}`;
}

startBtn.addEventListener('click', async function() {
    this.disabled = true;
    this.innerText = 'Warming...';
    
    idleState.classList.add('hidden');
    completeState.classList.add('hidden');
    activeState.classList.remove('hidden');
    
    startTime = new Date();
    timerInterval = setInterval(updateTimer, 1000);
    addLog('[WARMER] Batch operation started.', 'text-blue-400 font-bold');

    for (let i = 0; i < urlData.length; i++) {
        let item = urlData[i];
        
        // Update Path Display
        currentUrlDisplay.innerText = new URL(item.url).pathname;

        // Skip logic
        if (item.is_warmed) {
            updateProgress(i + 1);
            continue;
        }

        let success = false;
        let attempts = 0;

        while (!success && attempts < 3) {
            attempts++;
            attemptBadge.innerText = `#${attempts}`;
            
            if(attempts > 1) {
                retries++;
                retryCountEl.innerText = retries;
            }

            try {
                const response = await fetch(`/auth/warm-url?url=${encodeURIComponent(item.url)}`);
                const data = await response.json();
                
                if (response.ok && data.success) {
                    success = true;
                    item.is_warmed = true;
                    addLog(`[OK] ${new URL(item.url).pathname} (${data.size} bytes)`, 'text-slate-400');
                }
            } catch (e) {
                addLog(`[FAIL] Connection error on attempt ${attempts}`, 'text-red-400');
            }
            
            if(!success && attempts < 3) {
                await new Promise(r => setTimeout(r, 1000)); // Wait before retry
            }
        }
        
        updateProgress(i + 1);
        await new Promise(r => setTimeout(r, 50)); // Throttling
    }
    
    // Final UI State
    clearInterval(timerInterval);
    activeState.classList.add('hidden');
    completeState.classList.remove('hidden');
    this.innerText = 'Process Complete';
    this.classList.replace('bg-blue-600', 'bg-green-600');
    addLog('[FINISHED] All URLs processed.', 'text-green-500 font-bold');
});

function updateProgress(currentNum) {
    const percent = Math.round((currentNum / urlData.length) * 100);
    currentCountEl.innerText = currentNum;
    mainProgressBar.style.width = percent + '%';
    globalProgressBar.style.width = percent + '%';
    statusPercentEl.innerText = percent + '%';
}

/** AJAX Link Logic **/
document.querySelectorAll('.ajax-link').forEach(link => {
    link.addEventListener('click', async function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        this.classList.add('pointer-events-none', 'bg-blue-600', 'text-white');
        addLog(`[EXEC] Running script: ${url}`, 'text-yellow-500');

        try {
            const response = await fetch(url);
            const result = await response.text();
            addLog(`[SUCCESS] ${result}`, 'text-green-400');
        } catch (error) {
            addLog(`[ERROR] Script failed.`, 'text-red-500');
        }

        this.classList.remove('bg-blue-600', 'text-white');
        this.classList.add('bg-green-100', 'text-green-700');
        setTimeout(() => this.classList.remove('pointer-events-none', 'bg-green-100', 'text-green-700'), 2000);
    });
});

function addLog(msg, color) {
    const log = document.getElementById('logContent');
    const div = document.createElement('div');
    div.className = color;
    div.innerHTML = `<span class="text-slate-700">[${new Date().toLocaleTimeString()}]</span> ${msg}`;
    log.prepend(div);
}

document.getElementById('clearLog').addEventListener('click', () => {
    document.getElementById('logContent').innerHTML = '<div class="text-slate-600 italic">>> Console cleared.</div>';
});
</script>
@endpush