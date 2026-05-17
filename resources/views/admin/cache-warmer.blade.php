@extends('layout')

@section('content')
<div class="p-4 md:p-8 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Cloudflare Cache Warmer</h1>
                <p class="text-slate-500">Processing {{ count($urls) }} pages</p>
            </div>
            <button id="startWarmer" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all disabled:opacity-50">
                <span id="btnText">Start Warming Process</span>
            </button>
        </div>

        <!-- Progress Card -->
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8 mb-6">
            <div class="flex justify-between items-end mb-4">
                <div>
                    <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Progress Status</span>
                    <div class="text-4xl font-black text-slate-900">
                        <span id="currentCount">0</span> <span class="text-slate-300 text-2xl">/ {{ count($urls) }}</span>
                    </div>
                </div>
                <div id="percentage" class="text-2xl font-bold text-blue-600">0%</div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 rounded-full h-5 overflow-hidden shadow-inner mb-6">
                <div id="progressBar" class="bg-blue-600 h-full transition-all duration-300 ease-out" style="width: 0%"></div>
            </div>

            <!-- Current Activity Box -->
            <div id="activityBox" class="bg-slate-50 rounded-lg p-4 border border-slate-100 min-h-[80px] flex items-center">
                <div id="idleState" class="text-slate-400 italic w-full text-center">System Idle. Click start to begin warming.</div>
                
                <div id="activeState" class="hidden w-full">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Currently Caching:</span>
                        <span id="attemptBadge" class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold">Attempt #1</span>
                    </div>
                    <div id="currentUrlDisplay" class="text-sm font-mono text-slate-700 truncate break-all">---</div>
                </div>

                <div id="completeState" class="hidden w-full text-center">
                    <div class="text-green-600 font-bold flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        All {{ count($urls) }} Pages Cached Successfully!
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm text-center">
                <div class="text-xs text-slate-500 uppercase font-bold">Retries Triggered</div>
                <div id="retryCount" class="text-xl font-bold text-orange-500">0</div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm text-center">
                <div class="text-xs text-slate-500 uppercase font-bold">Time Elapsed</div>
                <div id="timer" class="text-xl font-bold text-slate-700">00:00</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const urls = @json($urls);
const currentCountEl = document.getElementById('currentCount');
const progressBar = document.getElementById('progressBar');
const percentageEl = document.getElementById('percentage');
const startBtn = document.getElementById('startWarmer');
const currentUrlDisplay = document.getElementById('currentUrlDisplay');
const attemptBadge = document.getElementById('attemptBadge');
const retryCountEl = document.getElementById('retryCount');
const timerEl = document.getElementById('timer');

// State UI sections
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
    activeState.classList.remove('hidden');
    
    startTime = new Date();
    timerInterval = setInterval(updateTimer, 1000);

    for (let i = 0; i < urls.length; i++) {
        let success = false;
        let attempts = 0;
        const url = urls[i];
        
        // Update "Now Caching" UI
        currentUrlDisplay.innerText = new URL(url).pathname;

        while (!success && attempts < 3) {
            attempts++;
            attemptBadge.innerText = `Attempt #${attempts}`;
            
            if(attempts > 1) {
                retries++;
                retryCountEl.innerText = retries;
            }

            try {
                const response = await fetch(`/auth/warm-url?url=${encodeURIComponent(url)}`);
                if (response.ok) {
                    success = true;
                }
            } catch (e) {
                // Connection error / 522
            }
            
            if(!success) await new Promise(r => setTimeout(r, 500));
        }

        // Update Progress
        const currentNum = i + 1;
        const percent = Math.round((currentNum / urls.length) * 100);
        currentCountEl.innerText = currentNum;
        progressBar.style.width = percent + '%';
        percentageEl.innerText = percent + '%';
    }
    
    // Final UI State
    clearInterval(timerInterval);
    activeState.classList.add('hidden');
    completeState.classList.remove('hidden');
    this.innerText = 'Process Complete';
    this.classList.replace('bg-blue-600', 'bg-green-600');
});
</script>
@endpush