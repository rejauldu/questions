@extends('layout')

@section('content')
<div class="min-h-screen bg-slate-50 p-8">
    <div class="max-w-6xl mx-auto bg-white shadow-xl rounded-lg">
        
        <!-- Toolbar -->
        <div class="bg-slate-800 p-4 flex justify-between items-center text-white rounded-t-lg">
            <span class="font-mono">Post ID: {{ $post->id }}, Next SVG({{ $svg ?? '' }})</span>
            <div class="space-x-4">
                <a href="{{ route('questions.show', $post->id) }}" class="px-3 md:px-4 py-1.5 md:py-2 bg-blue-600 text-white text-[10px] md:text-xs font-bold rounded-lg shadow-lg">View</a>
                <button id="saveSVG" class="bg-emerald-500 px-6 py-2 rounded font-bold">Save</button>
                <a href="{{ url('/auth/svg/' . ($post->id + 1)) }}" class="bg-blue-500 px-6 py-2 rounded font-bold">Next →</a>
                <a href="{{ route('questions.edit', $post->id) }}" class="px-3 md:px-4 py-1.5 md:py-2 bg-red-600 text-white text-[10px] md:text-xs font-bold rounded-lg shadow-lg">EDIT</a>
            </div>
        </div>

        <!-- Editable Area -->
        <div id="svg-editable" class="p-4 flex justify-center bg-slate-200 select-none">
            {!! $post->article ?? "" !!}
        </div>
    </div>
</div>

<!-- 1. Include your tool logic as a module -->
<script type="module" src="{{ asset('svg-editor/main.js') }}?2"></script>

<!-- 2. AJAX Code for Storing -->
<script>
    document.getElementById('saveSVG').addEventListener('click', function() {
        const btn = this;
        btn.innerText = 'Saving...';
        btn.disabled = true;

        // 1. Use the global reshaper attached in main.js to clear handles
        if (window.reshaper) {
            window.reshaper.clearHandles();
        }

        // 2. Prepare the clean version of the HTML
        const container = document.getElementById('svg-editable');
        const clone = container.cloneNode(true);
        
        // 3. Remove hitboxes
        clone.querySelectorAll('[data-type="hitbox"]').forEach(h => h.remove());
        
        // 4. Reset temporary visual styles (red/blue strokes)
        clone.querySelectorAll('*').forEach(el => {
            if (el.style) {
                el.style.stroke = "";
                el.style.outline = "";
            }
        });

        // 5. Send to Laravel
        fetch("{{ route('svg.save', $post->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                article: clone.innerHTML
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.innerText = 'Saved!';
            setTimeout(() => {
                btn.innerText = 'Save';
                btn.disabled = false;
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Save failed');
            btn.disabled = false;
        });
    });
</script>
@endsection