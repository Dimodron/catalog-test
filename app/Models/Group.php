<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $table = 'groups';
    public $timestamps = false;

    protected $fillable = ['id_parent', 'name'];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'id_parent');
    }
}
