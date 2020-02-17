<?php

namespace App\Entities;

use Illuminate\Contracts\Support\Arrayable;

class BaseEntity implements Arrayable
{
    public function toArray()
    {
        return [];
    }
}
