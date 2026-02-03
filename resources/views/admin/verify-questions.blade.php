@extends('layout')

@section('content')
    <div class="p-8 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto flex gap-6">
            
            <div class="w-1/2 bg-white p-6 rounded-lg shadow-md border-t-4 border-red-500">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-bold text-red-600">Original (Live)</h2>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded">ID: {{ $question->id }}</span>
                </div>
                <div class="space-y-4">
                    <p><strong>Q:</strong> {!! smart_nl2br($question->article) !!}</p>
                    <ul class="list-disc ml-5">
                        <li>ক: {!! $question->a !!}</li>
                        <li>খ: {!! $question->b !!}</li>
                        <li>গ: {!! $question->c !!}</li>
                        <li>ঘ: {!! $question->d !!}</li>
                    </ul>
                    <p class="text-green-600 font-bold">Ans: {{ $question->ans }}</p>
                    <p class="text-sm text-gray-500 italic">{!! smart_nl2br($question->explanation) !!}</p>
                </div>
            </div>

            <form action="{{ route('questions.verify', $question->id) }}" method="POST" id="correction-form" class="w-1/2 bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                @csrf
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-bold text-blue-600">Modified Data</h2>
                    <button type="button" id="fetch-ai" class="text-xs bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-200 transition">
                        ✨ Ask Gemini to Fix
                    </button>
                </div>
                
                <div id="loading-spinner" class="hidden text-sm text-gray-500 animate-pulse mb-2 text-center">Gemini is thinking...</div>

                <textarea name="article" id="input_article" class="w-full border rounded p-2 mb-2" rows="3">{{ $question->article }}</textarea>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" name="a" id="input_a" value="{{ $question->a }}" class="border p-2">
                    <input type="text" name="b" id="input_b" value="{{ $question->b }}" class="border p-2">
                    <input type="text" name="c" id="input_c" value="{{ $question->c }}" class="border p-2">
                    <input type="text" name="d" id="input_d" value="{{ $question->d }}" class="border p-2">
                </div>
                <input type="text" name="ans" id="input_ans" value="{{ $question->ans }}" class="w-full border p-2 mt-2 font-bold text-green-600">
                <textarea name="explanation" id="input_explanation" class="w-full border rounded p-2 mt-2 text-sm" rows="3">{{ $question->explanation }}</textarea>

                <div class="mt-6 flex justify-end gap-4">
                    <button name="action" value="pass" class="px-6 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Pass (No Change)</button>
                    <button name="action" value="update" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold">Update Database</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('fetch-ai').addEventListener('click', async function() {
            const btn = this;
            const spinner = document.getElementById('loading-spinner');
            
            btn.disabled = true;
            btn.classList.add('opacity-50');
            spinner.classList.remove('hidden');

            try {
                const response = await fetch("{{ route('ai.suggest', $question->id) }}");
                const data = await response.json();

                // Map the JSON keys to our input IDs
                document.getElementById('input_article').value = data.article;
                document.getElementById('input_a').value = data.a;
                document.getElementById('input_b').value = data.b;
                document.getElementById('input_c').value = data.c;
                document.getElementById('input_d').value = data.d;
                document.getElementById('input_ans').value = data.ans;
                document.getElementById('input_explanation').value = data.explanation;
                
                // Visual feedback
                document.getElementById('correction-form').classList.add('ring-2', 'ring-purple-400');
            } catch (error) {
                alert('Error fetching AI suggestion. Check Console.');
                console.error(error);
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-50');
                spinner.classList.add('hidden');
            }
        });
    </script>
@endsection
@push('scripts')
<script>
window.MathJax = {
  tex: {
    inlineMath: [['$', '$'], ['\\(', '\\)']],
    processEscapes: true
  },
  options: {
    enableMenu: false
  }
};
</script>

<script defer src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.MathJax && MathJax.startup) {
        MathJax.startup.promise.then(() => {
            MathJax.typeset();
        });
    }
});
</script>
@endpush