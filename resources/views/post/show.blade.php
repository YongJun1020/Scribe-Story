<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">

                {{-- Title --}}
                <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">{{ $post->title }}</h1>

                {{-- Author & Date --}}
                <div class="flex flex-col md:flex-row mt-6 justify-between items-start md:items-center gap-4">
                    <div class='flex flex-row items-center'>
                        <x-user-avatar :user="$post->user" size="h-14 w-14" imageType="profile" />
                        <x-follow-btn :user="$post->user" class="flex flex-col">
                            <div class="flex gap-2 ms-3 items-center">
                                <a href="{{ route('profile.show', $post->user->username) }}" class="hover:underline">
                                    <h3 class="font-bold text-gray-900">{{ $post->user->name }}</h3>
                                </a>
                                @if(auth()->check() && auth()->id() != $post->user->id)
                                    <span class="text-gray-300">&middot;</span>
                                    <button @click="follow()" 
                                            class="text-sm font-medium hover:underline cursor-pointer transition-colors" 
                                            x-text="following ? 'Unfollow' : 'Follow'" 
                                            :class="following ? 'text-red-600' : 'text-blue-600'">
                                    </button>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-1 ms-3 text-sm text-gray-500 items-center">
                                <span>{{ $post->readTime() }} {{ Str::plural('min', $post->readTime()) }} read</span>
                                <span>&middot;</span>
                                <span>{{ ($post->published_at ?? $post->created_at)->format('M d, Y') }}</span>
                                @if($post->updated_at->gt($post->published_at ?? $post->created_at))
                                    <span class="text-xs italic text-gray-400 ml-1">
                                        (Updated {{ $post->updated_at->diffForHumans() }})
                                    </span>
                                @endif
                            </div>
                        </x-follow-btn>
                    </div>

                    {{-- Actions (Edit/Delete) --}}
                    @if($post->user->id === auth()->id())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('post.edit', $post->slug) }}">
                                <x-button type="secondary" class="py-1.5 text-xs">
                                    Edit Post
                                </x-button>
                            </a>
                            <div x-data="{ showModal: false }" class="inline-block">
                                <x-button type="danger" @click="showModal = true" class="py-1.5 text-xs">
                                    Delete
                                </x-button>
                                <div x-show="showModal"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" 
                                    x-transition x-cloak>
                                    <div @click.away="showModal = false" class="bg-white p-8 rounded-xl shadow-2xl max-w-sm w-full mx-4">
                                        <h3 class="text-xl font-bold text-gray-900">Delete Post?</h3>
                                        <p class="text-gray-600 mt-2">This action cannot be undone. Are you sure you want to delete this post?</p>
                                        <div class="mt-8 flex justify-end gap-3">
                                            <button @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                                Cancel
                                            </button>
                                            <form action="{{ route('post.destroy', $post) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                                    Confirm Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <hr class="my-8 border-gray-100">

                {{-- Content Area --}}
                <div class="prose max-w-none">
                    @if($post->imageUrl())
                        <img class="w-full h-auto rounded-xl shadow-lg mb-8 object-cover max-h-[500px]"
                            src="{{ $post->imageUrl() }}"
                            alt="{{ $post->title }}">
                    @endif

                    <div class="text-lg text-gray-800 leading-relaxed whitespace-pre-line text-justify">
                        {{ $post->content }}
                    </div>
                </div>

                {{-- Footer Info --}}
                <div>
                    <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <div class="inline-flex items-center bg-gray-100 text-gray-700 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full">
                            {{ $post->category->name }}
                        </div>
                        <div class="flex items-center gap-3">
                            <x-like-button :post="$post" />
                            <x-comment-button :post="$post" />
                            <div x-data="{
                                copied: false,
                                copy() {
                                    navigator.clipboard.writeText(window.location.href);
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                }
                            }" class="flex items-center justify-center min-w-[60px]"> <button @click="copy()" class="transition-all hover:text-blue-500 flex items-center">
                                    <template x-if="!copied">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                                        </svg>
                                    </template>
                                    <template x-if="copied">
                                        <span class="text-green-600 font-bold text-xs animate-pulse">Copied!</span>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="comments" class="mt-6 pt-6 border-t border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900">Comments</h2>
                        <form action="{{ route('comment', $post) }}" method="POST" class="mt-3 group relative">
                            @csrf
                            <textarea
                                name="comment"
                                rows="1"
                                class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-400 focus:bg-white transition-all outline-none resize-none"
                                placeholder="Share your thoughts..." 
                                required></textarea>
                            <div class="flex justify-end">
                                <x-button type="secondary" class="!rounded-2xl">
                                    Send
                                </x-button>
                            </div>
                        </form>
                        <div class="mt-3 space-y-2">
                            @foreach($post->parentComment as $comment)
                                <div
                                    x-data="{ showReply: false }"
                                    class="group relative flex flex-col p-4 rounded-xl transition-all duration-200 hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-100"
                                    @click="if (!$event.target.closest('button, a, textarea, input')) showReply = !showReply"
                                >
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <x-user-avatar :user="$comment->user" size="h-9 w-9" imageType="profile" class="rounded-full shadow-sm" />
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <a href="{{ route('profile.show', $comment->user->username) }}" 
                                                class="font-bold text-gray-500 text-sm hover:underline">
                                                    {{ $comment->user->name }}
                                                </a>
                                            </div>
                                            <p class="text-gray-800 text-sm leading-relaxed">
                                                {{ $comment->comment }}
                                            </p>
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-500 text-xs">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                                <span class="text-xs text-gray-500 hover:underline cursor-pointer">
                                                    Click to Reply
                                                </span>
                                            </div>
                                            <div
                                                x-show="showReply"
                                                x-cloak
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                class="mt-4"
                                                @click.stop
                                            >
                                                <form action="{{ route('comment', $post) }}" method="POST" class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    <textarea
                                                        name="comment"
                                                        rows="2"
                                                        class="w-full p-3 text-sm bg-gray-50 border-none rounded-md focus:ring-2 focus:ring-blue-100 outline-none resize-none" 
                                                        placeholder="Write your reply..."
                                                        required
                                                    ></textarea>
                                                    <div class="flex justify-end gap-2 mt-2">
                                                        <button type="button" @click="showReply = false" class="text-xs font-semibold text-gray-500 px-3 py-1 hover:bg-gray-100 rounded">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-md hover:bg-blue-700 shadow-sm transition">
                                                            Send Reply
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($comment->replies->count() > 0)
                                    <div class="ml-12 mt-2 space-y-4 border-l-2 border-gray-100 pl-4 mb-6">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    <x-user-avatar :user="$reply->user" size="h-9 w-9" imageType="profile" class="rounded-full shadow-sm" />
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <a href="{{ route('profile.show', $reply->user->username) }}" 
                                                        class="font-bold text-gray-500 text-sm hover:underline">
                                                            {{ $reply->user->name }}
                                                        </a>
                                                    </div>
                                                    <p class="text-gray-800 text-sm leading-relaxed">
                                                        {{ $reply->comment }}
                                                    </p>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-gray-500 text-xs">
                                                            {{ $reply->created_at->diffForHumans() }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 hover:underline cursor-pointer">
                                                            Click to Reply
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if(!$loop->last)
                                    <hr class="my-8 border-gray-100">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
