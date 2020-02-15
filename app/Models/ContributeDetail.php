<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ContributeDetail extends Model
{
    protected $fillable = [
        'name',
        'twitter_id',
        'token',
    ];
}
