<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header with Back Button at the Left --}}
            <div class="flex flex-col gap-2 mb-6">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition w-fit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-4 mr-1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Back to Feed
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900">Create New Post</h1>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Image Upload --}}
                    <div x-data="{
                        imageUrl: null,
                        fileName: 'Click to upload',
                        fileChosen(event) {
                            const file = event.target.files[0];
                            if (!file) return;
                            this.fileName = file.name;
                            this.imageUrl = URL.createObjectURL(file);
                        }
                    }">
                        <x-input-label for="image" :value="__('Featured Image')" />
                        <div class="flex items-center justify-center w-full mt-1">
                            <label for="image"
                                :class="imageUrl ? 'border-solid border-blue-500' : 'border-dashed border-gray-300'"
                                :style="imageUrl ? `background-image: url('${imageUrl}'); background-size: cover; background-position: center;` : ''"
                                class="relative flex flex-col items-center justify-center w-full h-80 bg-gray-50 border-2 rounded-xl cursor-pointer hover:bg-gray-100 transition-all overflow-hidden group">
                                
                                <div :class="imageUrl ? 'bg-black/40 w-full h-full absolute inset-0 group-hover:bg-black/50 transition' : ''"></div>
                                
                                <div class="relative z-10 flex flex-col items-center justify-center text-center p-5" :class="imageUrl ? 'text-white' : 'text-gray-500'">
                                    <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm font-semibold" x-text="fileName"></p>
                                    <p class="text-xs mt-1 opacity-80">PNG, JPG or GIF (MAX. 2MB)</p>
                                </div>

                                <input id="image" name="image" type="file" class="hidden" accept="image/*" @change="fileChosen" />
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {{-- Title --}}
                        <div class="md:col-span-1">
                            <x-input-label for="title" :value="__('Post Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title')" :placeholder="__('Give your post a title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- Category --}}
                        <div class="md:col-span-1">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id"
                                class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="mt-6">
                        <x-input-label for="content" :value="__('Content')" />
                        <x-textarea id="content" class="block mt-1 w-full min-h-[200px]" name="content" :placeholder="__('Write your story here...')" required>
                            {{ old('content') }}
                        </x-textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    {{-- Published At --}}
                    <div class="mt-6">
                        <x-input-label for="published_at" :value="__('Schedule Publication (Optional)')" />
                        <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local" name="published_at"
                            :value="old('published_at')" />
                        <p class="text-xs text-gray-500 mt-1">Leave empty to publish immediately.</p>
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                            Cancel
                        </a>
                        <x-button type="primary">
                            Create Post
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>