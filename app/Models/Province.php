<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = ['id', 'name'];

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    public function regencies(): HasMany
    {
        return $this->hasMany(Regency::class, 'province_id');
    }
}
