<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SlackWorkspaceUser extends Model
{
    protected $fillable = [
        'channel_id',
        'slack_workspace_id',
        'channel_id',
        'slack_user_id'
    ];

    public function slack_workspace()
    {
        return $this->belongsTo(SlackWorkspace::class);

    }
}
