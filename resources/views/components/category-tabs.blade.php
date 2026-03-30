<ul class="flex flex-wrap text-sm font-medium text-center text-gray-600 justify-center gap-2">
    @if ($categories->isNotEmpty())
        <li>
            <a href="{{ route('dashboard') }}" 
                class="inline-block px-4 py-2 rounded-md transition-colors 
                {{ !request()->routeIs('post.byCategory') ? 'bg-gray-800 text-white shadow-sm' : 'bg-transparent hover:bg-gray-100 text-gray-700' }}">
                All
            </a>
        </li>
    @endif

    @forelse ($categories as $category)
        <li>
            <a href="{{ route('post.byCategory', $category) }}" 
                class="inline-block px-4 py-2 rounded-md transition-colors
                {{ request()->fullUrlIs(route('post.byCategory', $category)) ? 'bg-gray-800 text-white shadow-sm' : 'bg-transparent hover:bg-gray-100 text-gray-700' }}">
                {{ $category->name }}
            </a>
        </li>
    @empty
        <li class="text-gray-400 py-2">{{ $slot }}</li>
    @endforelse
</ul>