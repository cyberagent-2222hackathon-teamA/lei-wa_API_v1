<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $fillable = [
        'name',
        'twitter_id',
        'token',
    ];

    public function contributes(): HasMany
    {
        return $this->hasMany(ContributeSummary::class);
    }

}
