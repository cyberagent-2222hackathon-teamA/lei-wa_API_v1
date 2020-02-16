<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SlackWorkspaceUser extends Model
{
    protected $fillable = [

    ];

    public function slack_workspace()
    {
        return $this->belongsTo(SlackWorkspace::class);

    }
}
