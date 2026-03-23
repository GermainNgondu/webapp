<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'content',
        'slug',
        'props',
        'status',
        'published_at'
    ];

    protected $translatable = [
        'title',
        'description',
        'content',
        'slug',
        'props'
    ];

    protected $casts = [
        'content'=> 'array',
        'props' => 'array',
        'published_at'=> 'date'
    ];
}
