<section x-data="{ 
    imageUrl: '{{ $user->getFirstMedia('avatar') ? $user->imageUrl('profile') : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}',
    fileChosen(event) {
        const file = event.target.files[0];
        if (!file) return;
        this.imageUrl = URL.createObjectURL(file);
    }
}">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Avatar Section --}}
        <div>
            <x-input-label for="image" :value="__('Avatar')" />

            <div class="relative mt-2 w-24 h-24">
                <img :src="imageUrl"
                    alt="{{ $user->name }}" 
                    class="w-24 h-24 rounded-full object-cover border border-gray-300">

                <label for="image"
                    class="absolute bottom-0 right-0 bg-blue-600 p-2 rounded-full cursor-pointer shadow-sm hover:bg-blue-700 transition-colors border-2 border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <input id="image" name="image" type="file" class="hidden" accept="image/*" @change="fileChosen" />
                </label>
            </div>
            <x-input-error :messages="$errors->get('image')" class="mt-2" />
        </div>

        {{-- Username --}}
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('username', $user->username)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('name', $user->name)" required />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="underline text-sm text-blue-600 hover:text-blue-800">
                        {{ __('Click here to re-send.') }}
                    </button>
                </p>
            @endif
        </div>

        {{-- Bio --}}
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <x-textarea id="bio" class="block mt-1 w-full focus:border-blue-500 focus:ring-blue-500" name="bio">{{ old('bio', $user->bio) }}</x-textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-button type="primary">{{ __('Save') }}</x-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>