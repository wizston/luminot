<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $guarded = [];

    public function topics()
    {
         return $this->belongsToMany(Topic::class);
    }
}
