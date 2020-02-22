<?php

namespace App\Entities;

class UserEntity_xs extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * classを配列に変換
     * @return array
     */
    public function toArray()
    {

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
        ];
    }
}
