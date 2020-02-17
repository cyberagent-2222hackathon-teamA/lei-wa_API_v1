<?php

namespace App\Entities;

class ReactionEntity extends BaseEntity
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $count;

    /**
     * classを配列に変換
     * @return array
     */
    public function toArray()
    {

        return [
            'name'  => $this->name,
            'count' => $this->count,
        ];
    }
}
