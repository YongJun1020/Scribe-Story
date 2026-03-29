<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-category-tabs>
                        No Categories Found
                    </x-category-tabs>
                </div>
                <div class="my-4 px-5">
                    @forelse ($posts as $p)
                        <x-post-item :post="$p" />
                    @empty
                        <p class="text-center text-gray-500">No posts found.</p>
                    @endforelse
                    {{ $posts->onEachSide(1)->links() }}
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
