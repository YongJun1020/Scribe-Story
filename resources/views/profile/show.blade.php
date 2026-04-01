<x-app-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col-reverse md:flex-row gap-6 items-start">
                <div class="md:w-3/4 bg-white p-6 sm:p-8 shadow-sm border border-gray-100 rounded-2xl">
                    <div class="my-4">
                        <h1 class="text-2xl font-bold text-gray-900 border-b border-gray-100 pb-4">Latest Stories</h1>
                        <div class="mt-8 space-y-2">
                            @forelse ($posts as $p)
                                <x-post-item :post="$p" />
                            @empty
                                <p class="text-center text-gray-500 py-10">No posts found.</p>
                            @endforelse
                        </div>
                        <div class="mt-8">
                            {{ $posts->onEachSide(1)->links() }}
                        </div>
                    </div>
                    <hr class="my-8 border-gray-100">
                    @if (auth()->id() === $user->id)
                        <div x-data="{ openSection: null }">
                            <div class="overflow-hidden my-4">
                                <button @click="openSection = (openSection === 'following' ? null : 'following')" 
                                        class="w-full flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">Following ({{ $user->following->count() }})</span>
                                    <svg :class="openSection === 'following' ? 'rotate-180' : ''" class="w-5 h-5 text-gray-500 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openSection === 'following'" x-collapse>
                                    <div class="p-6 pt-0 border-t border-gray-50 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @forelse($user->following as $followingUser)
                                            <a href="{{ route('profile.show', $followingUser->username) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                                                <x-user-avatar :user="$followingUser" size="h-10 w-10" imageType="profile" class="rounded-full" />
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-900">{{ $followingUser->name }}</span>
                                                    <span class="text-xs text-gray-500">@<span>{{ $followingUser->username }}</span></span>
                                                </div>
                                            </a>
                                        @empty
                                            <p class="text-sm text-gray-500 col-span-full italic py-4">Not following anyone yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <hr class="my-8 border-gray-100">
                            <div class="overflow-hidden my-4">
                                <button @click="openSection = (openSection === 'followers' ? null : 'followers')" 
                                        class="w-full flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">Followers ({{ $user->followers->count() }})</span>
                                    <svg :class="openSection === 'followers' ? 'rotate-180' : ''" class="w-5 h-5 text-gray-500 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openSection === 'followers'" x-collapse>
                                    <div class="p-6 pt-0 border-t border-gray-50 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @forelse($user->followers as $follower)
                                            <a href="{{ route('profile.show', $follower->username) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                                                <x-user-avatar :user="$follower" size="h-10 w-10" imageType="profile" class="rounded-full" />
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-900">{{ $follower->name }}</span>
                                                    <span class="text-xs text-gray-500">@<span>{{ $follower->username }}</span></span>
                                                </div>
                                            </a>
                                        @empty
                                            <p class="text-sm text-gray-500 col-span-full italic py-4">No followers yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="md:w-1/4 md:sticky md:top-24">
                    <x-follow-btn :user="$user" class="bg-white p-8 shadow-sm border border-gray-100 rounded-2xl flex flex-col items-start">
                        <div class="relative inline-block mb-6">
                            <div class="p-1 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500">
                                <x-user-avatar :user="$user" size="h-24 w-24" imageType="profile" class="rounded-full object-cover" />
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 font-medium leading-none mt-1">@<span>{{ $user->username }}</span></p>
                        <div class="w-full mt-6 pt-6 border-t border-gray-50 flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-lg font-bold text-gray-900" x-text="formatNumber(followersCount)"></span>
                                <span class="text-[10px] uppercase tracking-widest font-bold text-gray-400" x-text="followersCount <= 1 ? 'Follower' : 'Followers'"></span>
                            </div>
                        </div>
                        @if($user->bio)
                            <p class="text-sm text-gray-600 mt-4 leading-relaxed line-clamp-4 italic">
                                "{{ $user->bio }}"
                            </p>
                        @endif
                        <div class="w-full mt-6">
                        @if(auth()->check())
                            @if(auth()->id() != $user->id)
                                <button @click="follow()"
                                    class="w-full rounded-full font-bold px-6 py-2.5 transition-all duration-200 shadow-sm flex items-center justify-center"
                                    x-text="following ? 'Unfollow' : 'Follow'"
                                    :class="following
                                        ? 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'
                                        : 'bg-black text-white hover:bg-gray-900'">
                                </button>
                            @else
                                <a href="{{ route('profile.edit') }}"
                                    class="w-full rounded-full font-bold px-6 py-2.5 transition-all duration-200 shadow-sm flex items-center justify-center bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    Edit Profile
                                </a>
                            @endif
                        @endif
                    </div>
                        <div class="mt-8 pt-4 border-t border-gray-50 w-full">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">
                                Member since {{ $user->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </x-follow-btn>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>