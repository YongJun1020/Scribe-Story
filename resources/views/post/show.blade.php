@section('seo_title', $post->title . ' — ' . $post->user->name)
@section('seo_description', Str::limit(strip_tags($post->content), 155))
@section('og_type', 'article')
@section('og_image', $post->imageUrl())

@push('seo_scripts')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ e($post->title) }}",
    "image": "{{ $post->imageUrl() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $post->user->name }}",
        "url": "{{ route('profile.show', $post->user->username) }}"
    },
    "datePublished": "{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "description": "{{ e(Str::limit(strip_tags($post->content), 155)) }}",
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}"
    }
}
</script>
@endpush

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
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <div class="inline-flex items-center bg-gray-100 text-gray-700 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full">
                        {{ $post->category->name }}
                    </div>
                    <x-like-button :post="$post" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>