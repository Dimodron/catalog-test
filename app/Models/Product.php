<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = ['id_group', 'name'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'id_product');
    }
}
