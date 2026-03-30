@props(['user', 'size' => 'w-24 h-24', 'imageType' => ''])

@if ($user->getFirstMedia('avatar'))
    <img class="{{ $size }} rounded-full object-cover" src="{{ $user->imageUrl($imageType) }}" alt="{{ $user->name }}">
@else
    <div class="{{ $size }} rounded-full bg-gray-300 flex items-center justify-center">
        <span class="text-gray-600 text-lg font-semibold">{{ Str::upper(Str::substr($user->name, 0, 1)) }}</span>
    </div>
@endif