<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Follow extends Model
{
    protected $fillable = [
        'follow_id',
        'followed_id',
    ];

    public function followed()
    {
        return $this->hasMany(User::class, "followed_id");
    }

}
