<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $fillable = [
        'name',
        'twitter_id',
        'profile_image_url',
        'token',
    ];

    public function contributes()
    {
        return $this->hasMany(ContributeSummary::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'follow_id');
    }

}
