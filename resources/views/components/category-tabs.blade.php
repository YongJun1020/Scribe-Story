<ul class="flex flex-wrap text-sm font-medium text-center text-body justify-center">
    <li class="me-2">
        <a href="#" class="inline-block px-4 py-2.5 text-white bg-blue-500 rounded-md active" aria-current="page">All</a>
    </li>
    @forelse ($categories as $category)
        <li class="me-2">
            <a href="#" class="inline-block px-4 py-3 rounded-md hover:text-black hover:bg-gray-200">{{ $category->name }}</a>
        </li>
    @empty
        <li class="me-2">
            <a href="#" class="inline-block px-4 py-3 rounded-md hover:text-black hover:bg-gray-200">{{ $slot }}</a>
        </li>
    @endforelse
</ul>