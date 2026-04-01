<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'content',
        'category_id',
        // 'image',
        'user_id',
        'slug',
        'published_at',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(400);
        // $this
        //     ->addMediaConversion('large')
        //     ->width(1200);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('post')
            // ->onlyKeepLatest(2);
            ->singleFile();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function readTime($wordsPerMinute = 100){
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / $wordsPerMinute);
        return max(1, $readingTime);
    }

    public function imageUrl($conversionName='')
    {
        // return $this->image ? Storage::url($this->image) : null;

        $media = $this->getFirstMedia('post');
        if(!$media){
            return null;
        }
        if ($media->hasGeneratedConversion($conversionName)) {
            return $media->getUrl($conversionName);
        }
        return $media->getUrl();
        // return $this->getFirstMedia('post')?->getUrl($conversionName);
    }

    public function userLike()
    {
        return $this->hasOne(Like::class)->where('user_id', auth()->id());
    }
}
