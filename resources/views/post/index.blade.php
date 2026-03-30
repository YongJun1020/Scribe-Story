<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-category-tabs>
                        No Categories Found
                    </x-category-tabs>
                </div>
                <div class="my-4 px-5 min-h-screen flex flex-col">
                    <div class="flex-1 flex flex-col">
                        @forelse ($posts as $p)
                            <x-post-item :post="$p" class="flex-1" />
                        @empty
                            <div class="flex-1 flex items-center justify-center">
                                <p class="text-center text-gray-500 text-lg">
                                    No posts found.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $posts->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
