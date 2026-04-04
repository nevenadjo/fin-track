<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['code'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
