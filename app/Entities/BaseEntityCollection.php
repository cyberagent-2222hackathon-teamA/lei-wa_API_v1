<?php

namespace App\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class BaseEntityCollection extends Collection implements Arrayable
{
    /**
     * @var mixed 総検索数などのmeta情報を入れることを想定
     */
    public $meta;

    public function meta($data)
    {
        $this->meta = $data;

        return $this;
    }

    public function toArray()
    {
        return array_map(function ($item) {
            return $item instanceof Arrayable ? $item->toArray() : null;
        }, $this->items);
    }
}
