{{-- This partial requires $post (for the form action) and $comments (for the list) --}}

<div class="mt-8">
    <h2 class="text-2xl font-extrabold text-secondary-900 mb-6 border-b pb-3 border-primary-300">
        Discussion ({{ count($comments ?? []) }})
    </h2>

    {{-- 1. Comment Form Section (Visible only to logged-in users) --}}
    @auth
        <div class="bg-white p-5 rounded-xl shadow-lg mb-8">
            <h3 class="text-lg font-bold text-secondary-800 mb-4">Post a Comment</h3>
            
            <form action="{{ route('comments.store', $post) }}" method="POST">
                @csrf

                <textarea name="body" rows="3" class="w-full p-3 border-2 border-secondary-300 rounded-lg focus:border-primary-500 focus:ring focus:ring-primary-200 transition duration-150 resize-none text-sm" placeholder="Share your thoughts or ask a follow-up question..." required></textarea>
                
                <div class="flex justify-end mt-3">
                    <button type="submit" class="bg-primary-600 text-white px-5 py-2 rounded-full text-sm font-bold shadow-md hover:bg-primary-700 transition duration-200 transform hover:scale-[1.02]">
                        Submit Comment
                    </button>
                </div>
            </form>
            
            {{-- Feedback Messages --}}
            @if(session('success'))
                <p class="mt-4 text-sm text-green-600 font-medium">{{ session('success') }}</p>
            @endif
            @error('body')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endauth
    
    {{-- 2. Call-to-Action for Guests --}}
    @guest
        <div class="bg-yellow-50 p-6 rounded-xl shadow-lg mb-8 text-center border-2 border-yellow-300">
            <h3 class="text-xl font-bold text-secondary-800 mb-3">Join the Discussion!</h3>
            <p class="text-secondary-700 mb-4">You must be logged in to post a comment or ask a question.</p>
            <a href="{{ url('/login') }}" class="bg-yellow-400 text-indigo-800 px-6 py-2 rounded-full font-bold shadow-md hover:bg-yellow-300 transition duration-200 transform hover:scale-[1.02]">
                Sign In to Comment
            </a>
        </div>
    @endguest

    {{-- 3. Display Comments List (Visible to everyone) --}}
    <div class="space-y-4">
        @forelse ($comments ?? [] as $comment)
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-secondary-200">
            <div class="flex items-center mb-2">
                {{-- Initials from the User's Name --}}
                <div class="w-7 h-7 rounded-full bg-info-100 text-info-700 font-bold flex items-center justify-center text-xs mr-3 flex-shrink-0">
                    {{ substr($comment->user->name ?? '', 0, 1) }}
                </div>
                <div>
                    {{-- Displaying Name from the related User model --}}
                    <p class="font-bold text-sm text-secondary-900">{{ $comment->user->name ?? 'Deleted User' }}</p>
                    <p class="text-xs text-secondary-500">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'Just now' }}</p>
                </div>
            </div>
            <p class="text-secondary-700 leading-relaxed text-sm pl-10">
                {{ $comment->body }}
            </p>
        </div>
        @empty
        <p class="text-center text-secondary-500 text-sm p-6 bg-secondary-50 rounded-lg border border-dashed border-secondary-300">
            No discussion yet. Be the first to post a comment!
        </p>
        @endforelse
    </div>
</div>