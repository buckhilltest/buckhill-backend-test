<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'uuid',
        'price',
        'description',
        'metadata'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function getMetadataAttribute($value)
    {
        return json_decode($value);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
