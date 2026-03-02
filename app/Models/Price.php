<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    protected $table = 'prices';
    public $timestamps = false;

    protected $fillable = ['id_product', 'price'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
