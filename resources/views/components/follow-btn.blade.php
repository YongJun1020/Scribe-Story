@props(['user'])

<div {{ $attributes }}x-data="{
                    following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
                    followersCount: {{ $user->followers()->count() }},
                    formatNumber(num) {
                        if (num >= 1000000) return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
                        if (num >= 1000) return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
                        return num;
                    },
                    follow() {
                        this.following = !this.following
                        axios.post('/follow/{{ $user->id }}').then(res => {
                            this.followersCount = res.data.followers
                        }).catch(err => {
                            console.log(err)
                        })
                    }
                }"
>
{{ $slot }}
</div>