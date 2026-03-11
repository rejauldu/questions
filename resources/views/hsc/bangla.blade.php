@extends('layout')

@section('seo')
@php
    // SEO Optimized Title and Description
    $title = "HSC English Mark Distribution 2026 (1st & 2nd Paper)";
    $description = "Check the latest HSC English 1st and 2nd paper mark distribution for 2026. Detailed breakdown of seen/unseen passages, grammar, and writing marks.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen text-gray-900 antialiased pb-12">

    <div class="max-w-2xl mx-auto px-3">
        
        <header class="py-6 text-center">
            {{-- Changed Roadmap to Mark Distribution --}}
            <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">
                HSC English <span class="text-indigo-600">Mark Distribution</span>
            </h1>
            <div class="flex items-center justify-center gap-2 mt-1">
                <span class="h-[1px] w-8 bg-indigo-200"></span>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Syllabus {{ date('Y') }}</p>
                <span class="h-[1px] w-8 bg-indigo-200"></span>
            </div>
        </header>

        {{-- English 1st Paper Section --}}
        <div id="paper1" class="mb-10 bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden">
            <div class="bg-indigo-600 px-4 py-3 flex justify-between items-center">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-tight">English 1st Paper</h2>
                    <p class="text-indigo-200 text-[9px] font-medium uppercase">Subject Code: 107</p>
                </div>
                <span class="text-xs font-black text-indigo-900 bg-indigo-100 px-2 py-1 rounded">100</span>
            </div>
            
            <div class="p-4">
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Part 1: Reading (60 Marks)</h3>
                
                <div class="space-y-0">
                    {{-- Item 1: Nested Seen Comprehension --}}
                    <div class="py-3 border-b border-gray-50">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">1.</span>
                            <div class="flex-1">
                                <span class="text-sm font-bold text-gray-800 block mb-3 uppercase tracking-tight">Seen Comprehension:</span>
                                
                                <div class="space-y-4">
                                    {{-- Sub Item A --}}
                                    <div class="flex justify-between items-center group pl-3 border-l-2 border-indigo-50">
                                        <div class="text-xs text-gray-600">
                                            <span class="font-bold text-gray-400">a)</span> Multiple Choice question <br>
                                            <span class="text-[10px] text-gray-400">(guessing meaning from context)</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-mono font-black text-slate-700 block">$0.5 \times 10 = 05$</span>
                                            <a href="/guide/mcq" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-medium transition-all group-hover:translate-x-0.5">
                                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                                            </a>
                                        </div>
                                    </div>
                                    {{-- Sub Item B --}}
                                    <div class="flex justify-between items-center group pl-3 border-l-2 border-indigo-50">
                                        <div class="text-xs text-gray-600">
                                            <span class="font-bold text-gray-400">b)</span> Comprehension questions
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-mono font-black text-slate-700 block">$2 \times 5 = 10$</span>
                                            <a href="/guide/short-answer" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-medium transition-all group-hover:translate-x-0.5">
                                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Question 2 --}}
                    <div class="py-3 border-b border-gray-50 flex justify-between items-start">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">2.</span>
                            <div>
                                <span class="text-sm font-bold text-gray-800 block uppercase tracking-tight">Seen Comprehension:</span>
                                <span class="text-xs text-gray-600">Flow Chart/Information Transfer</span>
                            </div>
                        </div>
                        <div class="text-right group">
                            <span class="text-xs font-mono font-black text-slate-700 block">$2 \times 5 = 10$</span>
                            <a href="/guide/flow-chart" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-medium transition-all group-hover:translate-x-0.5">
                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>

                    {{-- Question 3 --}}
                    <div class="py-3 border-b border-gray-50 flex justify-between items-start">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">3.</span>
                            <div>
                                <span class="text-sm font-bold text-gray-800 block uppercase tracking-tight">Seen Comprehension:</span>
                                <span class="text-xs text-gray-600">Summarizing</span>
                            </div>
                        </div>
                        <div class="text-right group">
                            <span class="text-xs font-mono font-black text-slate-700 block">$1 \times 10 = 10$</span>
                            <a href="/guide/summary" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-medium transition-all group-hover:translate-x-0.5">
                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>

                    {{-- Unseen Items --}}
                    @php
                        $unseen1st = [
                            ['no' => '4', 'item' => 'Cloze test with clues', 'label' => '(Unseen)', 'marks' => '05', 'dist' => '0.5 \times 10', 'url' => 'cloze-with'],
                            ['no' => '5', 'item' => 'Cloze test without clues', 'label' => '(Unseen)', 'marks' => '10', 'dist' => '1 \times 10', 'url' => 'cloze-without'],
                            ['no' => '6', 'item' => 'Rearranging', 'label' => '(Unseen)', 'marks' => '10', 'dist' => '1 \times 10', 'url' => 'rearrange']
                        ];
                    @endphp

                    @foreach($unseen1st as $row)
                    <div class="py-3 border-b border-gray-50 last:border-0 flex justify-between items-start">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">{{ $row['no'] }}.</span>
                            <div>
                                <span class="text-sm font-bold text-gray-800 block leading-tight">{{ $row['item'] }}</span>
                                <span class="text-xs text-gray-400">{{ $row['label'] }}</span>
                            </div>
                        </div>
                        <div class="text-right group">
                            <span class="text-xs font-mono font-black text-slate-700 block">
                                ${{ $row['dist'] }} = {{ $row['marks'] }}$
                            </span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-medium transition-all group-hover:translate-x-0.5">
                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Part 2: Writing --}}
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mt-8 mb-2">Part 2: Writing (40 Marks)</h3>
                <div class="space-y-0">
                    @php
                        $writing1st = [
                            ['no' => '7', 'item' => 'Paragraph Writing', 'label' => '(Answering Questions)', 'marks' => '10', 'url' => 'paragraph'],
                            ['no' => '8', 'item' => 'Completing a Story', 'label' => '(Creative Writing)', 'marks' => '07', 'url' => 'story'],
                            ['no' => '9', 'item' => 'Informal Letter / E-mail', 'label' => '(Personal)', 'marks' => '05', 'url' => 'email'],
                            ['no' => '10', 'item' => 'Analyzing Graphs / Charts', 'label' => '(Data interpretation)', 'marks' => '10', 'url' => 'graphs'],
                            ['no' => '11', 'item' => 'Appreciating Poems', 'label' => '(Theme writing)', 'marks' => '08', 'url' => 'theme']
                        ];
                    @endphp
                    @foreach($writing1st as $row)
                    <div class="py-3 border-b border-gray-50 last:border-0 flex justify-between items-start">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">{{ $row['no'] }}.</span>
                            <div>
                                <span class="text-sm font-bold text-gray-800 block leading-tight">{{ $row['item'] }}</span>
                                <span class="text-xs text-gray-400">{{ $row['label'] }}</span>
                            </div>
                        </div>
                        <div class="text-right group">
                            <span class="text-sm font-mono font-black text-slate-700 block">{{ $row['marks'] }}</span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-medium transition-all group-hover:translate-x-0.5">
                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- English 2nd Paper Section --}}
        <div id="paper2" class="mb-10 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-800 px-4 py-3 flex justify-between items-center">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-tight">English 2nd Paper</h2>
                    <p class="text-slate-400 text-[9px] font-medium uppercase">Subject Code: 108</p>
                </div>
                <span class="text-xs font-black text-slate-800 bg-slate-100 px-2 py-1 rounded">100</span>
            </div>

            <div class="p-4">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Part A: Grammar (60 Marks)</h3>
                <div class="space-y-0">
                    @php
                        $grammar2nd = [
                            ['no' => '1', 'item' => 'Articles', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'articles'],
                            ['no' => '2', 'item' => 'Prepositions', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'prepositions'],
                            ['no' => '3', 'item' => 'Special Phrases', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'special-phrases'],
                            ['no' => '4', 'item' => 'Completing Sentences', 'dist' => '1 \times 5', 'marks' => '05', 'url' => 'completing-sentences'],
                            ['no' => '5', 'item' => 'Right Form of Verbs', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'verbs'],
                            ['no' => '6', 'item' => 'Changing Sentences', 'dist' => '1 \times 5', 'marks' => '05', 'url' => 'changing-sentences'],
                            ['no' => '7', 'item' => 'Narrative Style', 'dist' => '1 \times 5', 'marks' => '05', 'url' => 'narrative'],
                            ['no' => '8', 'item' => 'Pronoun Reference', 'dist' => '1 \times 5', 'marks' => '05', 'url' => 'pronoun'],
                            ['no' => '9', 'item' => 'Use of Modifiers', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'modifiers'],
                            ['no' => '10', 'item' => 'Sentence Connectors', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'connectors'],
                            ['no' => '11', 'item' => 'Synonyms & Antonyms', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'synonym-antonym'],
                            ['no' => '12', 'item' => 'Punctuation', 'dist' => '0.5 \times 10', 'marks' => '05', 'url' => 'punctuation']
                        ];
                    @endphp
                    @foreach($grammar2nd as $row)
                    <div class="py-3 border-b border-gray-50 last:border-0 flex justify-between items-center">
                        <div class="flex gap-3 items-center">
                            <span class="text-xs font-bold text-indigo-300 w-7">{{ $row['no'] }}.</span>
                            <span class="text-sm font-bold text-gray-800 leading-tight">{{ $row['item'] }}</span>
                        </div>
                        <div class="text-right group">
                            <span class="text-xs font-mono font-black text-slate-700 block">
                                ${{ $row['dist'] }} = {{ $row['marks'] }}$
                            </span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-slate-800 font-medium transition-all group-hover:translate-x-0.5">
                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-8 mb-2">Part B: Composition (40 Marks)</h3>
                <div class="space-y-0">
                    @php
                        $writing2nd = [
                            ['no' => '13', 'item' => 'Formal Letter', 'marks' => '10', 'url' => 'application'],
                            ['no' => '14', 'item' => 'Report Writing', 'marks' => '08', 'url' => 'report'],
                            ['no' => '15', 'item' => 'Paragraph Writing', 'marks' => '10', 'url' => 'paragraph-2nd'],
                            ['no' => '16', 'item' => 'Composition / Essay', 'marks' => '12', 'url' => 'essay']
                        ];
                    @endphp
                    @foreach($writing2nd as $row)
                    <div class="py-3 border-b border-gray-50 last:border-0 flex justify-between items-center">
                        <div class="flex gap-3 items-center">
                            <span class="text-xs font-bold text-indigo-300 w-7">{{ $row['no'] }}.</span>
                            <span class="text-sm font-bold text-gray-800 leading-tight">{{ $row['item'] }}</span>
                        </div>
                        <div class="text-right group">
                            <span class="text-sm font-mono font-black text-slate-700 block">{{ $row['marks'] }}</span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-slate-800 font-medium transition-all group-hover:translate-x-0.5">
                                Suggestion <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection