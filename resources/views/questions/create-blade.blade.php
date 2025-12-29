@extends('layout')

@section('content')
<div class="min-h-screen bg-secondary-100 py-6">
    <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-xl p-4 sm:p-6 md:p-8">

        {{-- Page Header --}}
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary-700 mb-6 border-b pb-4 text-center">
            Create New Question
        </h1>

        {{-- Form Start - Added onsubmit validation --}}
        <form action="{{ route('questions.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              onsubmit="return validateForm()" 
              class="space-y-8">
            @csrf

            {{-- 1. Metadata Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 p-4 bg-secondary-50 rounded-xl border border-secondary-200">
                <div>
                    <label class="text-[10px] font-bold text-secondary-500 uppercase tracking-widest">Institution</label>
                    <select name="institution_id" id="institution_id" onchange="loadSubjects(this.value)" class="w-full mt-1 border-secondary-300 rounded-lg text-sm focus:ring-primary-500">
                        <option value="">Select</option>
                        @foreach($institutions as $i)
                            <option value="{{ $i->id }}" {{ request('institution_id') == $i->id ? 'selected' : '' }}>{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-secondary-500 uppercase tracking-widest">Subject</label>
                    <select name="subject_id" id="subject_id" data-selected="{{ request('subject_id') }}" class="w-full mt-1 border-secondary-300 rounded-lg text-sm focus:ring-primary-500">
                        <option value="">Select Institution</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-secondary-500 uppercase tracking-widest">Board</label>
                    <select name="board_id" class="w-full mt-1 border-secondary-300 rounded-lg text-sm focus:ring-primary-500">
                        <option value="">Select Board</option>
                        @foreach($boards as $b)
                            <option value="{{ $b->id }}" {{ request('board_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-secondary-500 uppercase tracking-widest">Year</label>
                    <select name="year" class="w-full mt-1 border-secondary-300 rounded-lg text-sm focus:ring-primary-500">
                        <option value="">Year</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-secondary-500 uppercase tracking-widest">Chapter</label>
                    <input name="chapter" value="{{ request('chapter') }}" class="w-full mt-1 border-secondary-300 rounded-lg text-sm focus:ring-primary-500" placeholder="e.g. Chapter 01" />
                </div>
            </div>

            {{-- 2. Category Selection --}}
            <div class="p-4 bg-primary-50 rounded-xl border border-primary-100">
                <label class="text-[11px] font-black text-primary-700 uppercase tracking-widest">Question Type / Category</label>
                <select name="category" id="category_select" onchange="toggleCategory(this.value)" class="w-full md:w-1/4 mt-1 border-primary-300 rounded-lg text-base font-bold text-primary-800 focus:ring-primary-500 shadow-sm">
                    <option value="MCQ" {{ request('category') == 'MCQ' ? 'selected' : '' }}>MCQ (Multiple Choice)</option>
                    <option value="CQ" {{ request('category') == 'CQ' ? 'selected' : '' }}>CQ (Creative / Written)</option>
                    <option value="Writing" {{ request('category') == 'Writing' ? 'selected' : '' }}>Writing</option>
                </select>
            </div>

            {{-- 3. Article (Question Stem) --}}
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <label class="font-bold text-primary-800 text-lg">Article (উদ্দীপক / প্রশ্ন)</label>
                    <div class="flex gap-2">
                        <input type="file" id="inlineImageInput" class="hidden" accept="image/*" onchange="handleInlineUpload(this)" />
                        <button type="button" onclick="document.getElementById('inlineImageInput').click()" class="text-[11px] uppercase font-black bg-white text-primary-600 px-3 py-1.5 rounded-lg border border-primary-200 hover:bg-primary-50 transition shadow-sm">
                            + Add Inline Image
                        </button>
                    </div>
                </div>
                <textarea name="article" id="article"
                    class="w-full border-secondary-300 rounded-xl shadow-inner min-h-[180px] text-base focus:ring-2 focus:ring-primary-500" 
                    placeholder="Write question details here...">{{ request('article') }}</textarea>
            </div>

            {{-- NEW: Full Width Question Image Section --}}
            <div class="space-y-3">
                <label class="font-bold text-primary-800 text-lg flex items-center gap-2">
                    Question Images (প্রশ্নের ছবি / বিকল্প)
                    <span class="text-[10px] font-normal text-secondary-500 uppercase tracking-widest">(Use if not using text)</span>
                </label>
                <div class="relative border-2 border-dashed border-secondary-300 rounded-xl p-8 bg-secondary-50 hover:bg-white transition cursor-pointer group">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" 
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                        onchange="previewImages(this)">
                    
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-secondary-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-secondary-600 font-bold uppercase tracking-wide">Click to upload Question Images</p>
                        <p class="text-xs text-secondary-400 mt-1">Upload screenshots or scans if the question is not typed</p>
                    </div>
                </div>
                <div id="image_preview_container" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4 mt-4"></div>
            </div>

            {{-- 4. MCQ Options Section --}}
            <div id="mcq_section" class="grid grid-cols-2 gap-6 p-6 bg-secondary-50 rounded-2xl border-2 border-dashed border-secondary-200 mt-4">
                @foreach(['a', 'b', 'c', 'd'] as $opt)
                    <div class="group">
                        <label class="text-[10px] font-bold text-secondary-400 uppercase ml-1">Option {{ strtoupper($opt) }}</label>
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-white border border-secondary-300 flex items-center justify-center font-bold text-secondary-500 group-focus-within:bg-primary-600 group-focus-within:text-white transition shadow-sm">
                                {{ strtoupper($opt) }}
                            </span>
                            <input name="{{ $opt }}" value="{{ request($opt) }}"
                                class="w-full border-secondary-300 rounded-lg text-sm px-4 py-2.5 shadow-sm focus:ring-primary-500" 
                                placeholder="Type option here..." />
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 5. Solutions & Explanations --}}
            <div class="space-y-6">
                <div id="solution_container" class="space-y-4">
                    <div class="space-y-3">
                        <label class="font-bold text-primary-800">Explanation (ব্যাখ্যা)</label>
                        <textarea name="explanation" 
                            class="w-full border-secondary-300 rounded-xl text-sm min-h-[100px] focus:ring-primary-500 shadow-inner bg-gray-50" 
                            placeholder="Why is this answer correct? Provide context...">{{ request('explanation') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 6. Bottom Metadata & Submit --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end pt-8 border-t border-secondary-100">
                <div>
                    <label class="text-[10px] font-bold text-secondary-500 uppercase tracking-widest">Target Class</label>
                    <select name="class" class="w-full mt-1 border-secondary-300 rounded-lg text-sm focus:ring-primary-500">
                        <option value="">Select Class</option>
                        @foreach($classes as $c)
                            <option value="{{ $c['value'] }}" {{ request('class') == $c['value'] ? 'selected' : '' }}>{{ $c['text'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="ans_field_container">
                    <label id="ans_label" class="text-[10px] font-bold text-secondary-500 uppercase tracking-widest">Correct Option (e.g. A)</label>
                    <input name="ans" id="ans_input" value="{{ request('ans') }}" class="w-full mt-1 border-secondary-300 rounded-lg text-sm py-2 px-4 font-bold text-primary-700 focus:ring-primary-500" placeholder="A" />
                </div>

                <button type="submit" class="w-full py-3 bg-primary-600 text-white font-extrabold rounded-xl hover:bg-primary-700 shadow-lg transform transition active:scale-95 uppercase tracking-wider">
                    Save Question
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    /**
     * NEW: Form Validation Function
     */
    function validateForm() {
        const institutionSelect = document.getElementById('institution_id');
        
        if (institutionSelect.value === "" || institutionSelect.value === null) {
            alert("Please select an Institution before saving.");
            institutionSelect.focus();
            // Scroll to the top where the metadata grid is
            institutionSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false; // Prevents form submission
        }
        
        return true; // Allows form submission
    }

    function toggleCategory(val) {
        const mcqSection = document.getElementById('mcq_section');
        const ansLabel = document.getElementById('ans_label');
        const ansInput = document.getElementById('ans_input');

        if (val === 'MCQ') {
            mcqSection.classList.remove('hidden');
            ansLabel.innerText = "Correct Option (e.g. A)";
            ansInput.placeholder = "A";
        } else {
            mcqSection.classList.add('hidden');
            ansLabel.innerText = "Answer / Mark (e.g. 10)";
            ansInput.placeholder = "Enter short answer or marks";
        }
    }

    function previewImages(input) {
        const container = document.getElementById('image_preview_container');
        container.innerHTML = ''; 

        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-lg border border-secondary-300 overflow-hidden shadow-sm bg-white';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    async function loadSubjects(id, selectedId = null) {
        const subjectSelect = document.getElementById('subject_id');
        if (!id) {
            subjectSelect.innerHTML = '<option value="">Select Institution</option>';
            return;
        }
        subjectSelect.innerHTML = '<option value="">Loading...</option>';
        
        try {
            const res = await axios({
                method: 'get',
                url: "{{ route('api.posts.subjects-by-institution') }}",
                params: { institution_id: id },
                headers: { 'Accept': 'application/json' }
            });

            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            const data = Array.isArray(res.data) ? res.data : [res.data];
            
            data.forEach(s => {
                if(s && s.id) {
                    const isSelected = (selectedId == s.id) ? 'selected' : '';
                    subjectSelect.innerHTML += `<option value="${s.id}" ${isSelected}>${s.name}</option>`;
                }
            });
        } catch (e) { 
            console.error("Failed to load subjects:", e);
            subjectSelect.innerHTML = '<option value="">Error Loading</option>';
        }
    }

    async function handleInlineUpload(input) {
        const file = input.files[0];
        if (!file) return;
        const formData = new FormData();
        formData.append('image', file);
        try {
            const res = await axios.post("{{ route('api.image.upload') }}", formData);
            const imgTag = `\n<img src="${res.data.url}" alt="diagram" class="max-w-full h-auto my-2" />\n`;
            const article = document.getElementById('article');
            article.value += imgTag;
        } catch (e) { alert("Upload failed."); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleCategory(document.getElementById('category_select').value);
    });
</script>
@endpush