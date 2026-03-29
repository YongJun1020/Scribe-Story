<div class="flex flex-col items-stretch hover:bg-gray-100 transition duration-200 gap-10 border border-default rounded-md shadow-xs md:flex-row md:max-w-full mb-4">
    <div class="flex flex-col flex-1 justify-between py-4 pl-4 leading-normal">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-heading">{{ $post->title }}</h5>
        <p class="mb-6 text-body text-justify">{{ Str::words($post->content, 25) }}</p>
        <a href="">
            <x-primary-button>
                Read more
                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
            </x-primary-button>
        </a>
    </div>
    <img class="justify-end object-cover w-36 rounded-r-md md:h-auto md:w-72 mb-4 md:mb-0 md:p-0" src="{{ Storage::url($post->image) }}" alt="">
</div>