<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Storage;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_url'];

    public $timestamps = false;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->attributes['image_url'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}