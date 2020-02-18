<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributeSummary extends Model
{
    protected $fillable = [
        'user_id',
        'post_count',
        'reaction_count',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
