<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title', 'excerpt', 'content'];

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'author',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'views_count',
        'sort_order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($publishedQuery) {
                $publishedQuery
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
