@extends('layout')

@section('seo')
@php
    $title = "HSC English Mark Distribution 2026 (Revised Syllabus)";
    $description = "Check the updated HSC English 1st and 2nd paper mark distribution for 2026. Includes 15 marks for Graph/Chart and Story Writing with solving guides.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen text-gray-900 antialiased font-sans">

    <div class="max-w-2xl mx-auto px-3">
        
        <header class="py-6 text-center">
            <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">
                HSC English <span class="text-indigo-600">Mark Distribution</span>
            </h1>
            <div class="flex items-center justify-center gap-2 mt-1">
                <span class="h-[1px] w-8 bg-indigo-200"></span>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Syllabus 2026</p>
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
                {{-- Part 1: Reading (60 Marks) --}}
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Part 1: Reading (60 Marks)</h3>
                
                <div class="space-y-0">
                    {{-- Item 1: MCQ & Short Answer --}}
                    <div class="py-3 border-b border-gray-50">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">1.</span>
                            <div class="flex-1">
                                <span class="text-sm font-bold text-gray-800 block mb-3 uppercase tracking-tight">Seen Comprehension:</span>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center group pl-3 border-l-2 border-indigo-50">
                                        <div class="text-xs text-gray-600">
                                            <span class="font-bold text-gray-400">A)</span> Multiple Choice question
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-mono font-black text-slate-700 block">$0.5 \times 10 = 05$</span>
                                            <a href="/guide/mcq" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-bold transition-all group-hover:translate-x-0.5">
                                                উত্তর করার নিয়ম <x-icons.chevron-right class="w-2.5 h-2.5" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center group pl-3 border-l-2 border-indigo-50">
                                        <div class="text-xs text-gray-600">
                                            <span class="font-bold text-gray-400">B)</span> Short Answer questions
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-mono font-black text-slate-700 block">$3 \times 5 = 15$</span>
                                            <a href="/guide/short-answer" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-bold transition-all group-hover:translate-x-0.5">
                                                উত্তর করার নিয়ম <x-icons.chevron-right class="w-2.5 h-2.5" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary, Flow Chart & Unseen --}}
                    @php
                        $reading1st = [
                            ['no' => '2', 'item' => 'Information Transfer / Flow Chart', 'marks' => '05', 'dist' => '1 \times 5', 'url' => 'flow-chart'],
                            ['no' => '3', 'item' => 'Summarizing', 'marks' => '10', 'dist' => '1 \times 10', 'url' => 'summary'],
                            ['no' => '4', 'item' => 'Cloze test with clues', 'marks' => '05', 'dist' => '0.5 \times 10', 'url' => 'cloze-with'],
                            ['no' => '5', 'item' => 'Cloze test without clues', 'marks' => '10', 'dist' => '1 \times 10', 'url' => 'cloze-without'],
                            ['no' => '6', 'item' => 'Rearranging', 'marks' => '10', 'dist' => '1 \times 10', 'url' => 'rearrange']
                        ];
                    @endphp

                    @foreach($reading1st as $row)
                    <div class="py-3 border-b border-gray-50 flex justify-between items-start group">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">{{ $row['no'] }}.</span>
                            <span class="text-sm font-bold text-gray-800 block leading-tight">{{ $row['item'] }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-mono font-black text-slate-700 italic block">${{ $row['dist'] }} = {{ $row['marks'] }}$</span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-bold transition-all group-hover:translate-x-0.5">
                                উত্তর করার নিয়ম <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Part 2: Writing (40 Marks) - Updated --}}
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mt-8 mb-2">Part 2: Writing (40 Marks)</h3>
                <div class="space-y-0">
                    @php
                        $writing1st = [
                            ['no' => '7', 'item' => 'Describing Graph / Chart', 'label' => '(Data Interpretation)', 'marks' => '15', 'url' => 'graphs'],
                            ['no' => '8', 'item' => 'Completing a Story', 'label' => '(Creative Writing)', 'marks' => '15', 'url' => 'completing-story'],
                            ['no' => '9', 'item' => 'Informal Letter', 'label' => '(Personal/Email)', 'marks' => '10', 'url' => 'informal-letter']
                        ];
                    @endphp
                    @foreach($writing1st as $row)
                    <div class="py-4 border-b border-gray-50 last:border-0 flex justify-between items-start group">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-indigo-300 w-7">{{ $row['no'] }}.</span>
                            <div>
                                <span class="text-sm font-bold text-gray-900 block leading-tight">{{ $row['item'] }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $row['label'] }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-mono font-black text-indigo-600 block">{{ $row['marks'] }}</span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-bold transition-all group-hover:translate-x-0.5">
                                উত্তর করার নিয়ম <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- English 2nd Paper Section --}}
        <div id="paper2" class="mb-12 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-800 px-4 py-3 flex justify-between items-center">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-tight">English 2nd Paper</h2>
                    <p class="text-slate-400 text-[9px] font-medium uppercase">Subject Code: 108</p>
                </div>
                <span class="text-xs font-black text-white bg-slate-700 px-2 py-1 rounded">100</span>
            </div>
            
            <div class="p-4">
                {{-- Part 1: Grammar (60 Marks) --}}
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Part 1: Grammar (60 Marks)</h3>
                
                <div class="space-y-0">
                    @php
                        $grammarItems = [
                            ['no' => '1', 'item' => 'Prepositions', 'marks' => '05', 'dist' => '0.5 \times 10', 'url' => 'preposition'],
                            ['no' => '2', 'item' => 'Words/Phrases (Special Uses)', 'marks' => '05', 'dist' => '0.5 \times 10', 'url' => 'special-phrases'],
                            ['no' => '3', 'item' => 'Completing Sentences', 'marks' => '10', 'dist' => '1 \times 10', 'url' => 'completing-sentences'],
                            ['no' => '4', 'item' => 'Right form of Verbs', 'marks' => '09', 'dist' => '0.5 \times 18', 'url' => 'right-form-of-verbs'],
                            ['no' => '5', 'item' => 'Narrative Style (Direct/Indirect)', 'marks' => '07', 'dist' => '7 \times 1', 'url' => 'narration'],
                            ['no' => '6', 'item' => 'Modifier', 'marks' => '05', 'dist' => '0.5 \times 10', 'url' => 'modifier'],
                            ['no' => '7', 'item' => 'Sentence Connectors', 'marks' => '07', 'dist' => '0.5 \times 14', 'url' => 'connectors'],
                            ['no' => '8', 'item' => 'Synonym & Antonym', 'marks' => '07', 'dist' => '0.5 \times 14', 'url' => 'synonym-antonym'],
                            ['no' => '9', 'item' => 'Punctuation', 'marks' => '05', 'dist' => '0.5 \times 10', 'url' => 'punctuation']
                        ];
                    @endphp

                    @foreach($grammarItems as $row)
                    <div class="py-3 border-b border-gray-50 flex justify-between items-start group">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-slate-300 w-7">{{ $row['no'] }}.</span>
                            <span class="text-sm font-bold text-slate-700 block leading-tight">{{ $row['item'] }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-mono font-black text-slate-600 italic block">${{ $row['dist'] }} = {{ $row['marks'] }}$</span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-bold transition-all group-hover:translate-x-0.5">
                                উত্তর করার নিয়ম <x-icons.chevron-right class="w-2.5 h-2.5" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Part 2: Composition (40 Marks) --}}
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-8 mb-2">Part 2: Composition (40 Marks)</h3>
                <div class="space-y-0">
                    @php
                        $composition2nd = [
                            ['no' => '10', 'item' => 'Formal Letter Writing', 'label' => '(Application/Complaint)', 'marks' => '10', 'url' => 'formal-letter'],
                            ['no' => '11', 'item' => 'Writing Paragraph', 'label' => '(Listing/Description)', 'marks' => '15', 'url' => 'paragraph-listing-description'],
                            ['no' => '12', 'item' => 'Writing Paragraph', 'label' => '(Comparison/Cause-Effect)', 'marks' => '15', 'url' => 'paragraph-cause-effect']
                        ];
                    @endphp
                    @foreach($composition2nd as $row)
                    <div class="py-4 border-b border-gray-50 last:border-0 flex justify-between items-start group">
                        <div class="flex gap-3">
                            <span class="text-xs font-bold text-slate-300 w-7">{{ $row['no'] }}.</span>
                            <div>
                                <span class="text-sm font-bold text-slate-800 block leading-tight">{{ $row['item'] }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $row['label'] }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-mono font-black text-slate-700 block">{{ $row['marks'] }}</span>
                            <a href="/guide/{{ $row['url'] }}" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 font-bold transition-all group-hover:translate-x-0.5">
                                উত্তর করার নিয়ম <x-icons.chevron-right class="w-2.5 h-2.5" />
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