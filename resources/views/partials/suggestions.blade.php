<div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-primary-600">
    <h3 class="text-lg font-bold text-secondary-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-warning-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
        আপনার জন্য প্রস্তাবিত (Related for You)
    </h3>
    
    <div class="space-y-3">
        @forelse($suggestions as $item)
            <a href="{{ route('questions.show', [$item->id, url_slug($item->article)]) }}" class="block p-3 hover:bg-secondary-50 rounded-lg border border-transparent hover:border-secondary-200 transition">
                <p class="text-sm text-secondary-800 line-clamp-2">{!! strip_tags($item->article) !!}</p>
                <span class="text-[10px] text-primary-500 font-bold uppercase">{{ $item->institution->name ?? 'Update' }}</span>
            </a>
        @empty
            <p class="text-xs text-secondary-500 italic">কিছু প্রশ্ন পড়ুন, আমরা আপনার পছন্দ অনুযায়ী সাজেশন দেব।</p>
        @endforelse
    </div>
</div>