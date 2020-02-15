<?php

namespace App\Entities;

class UserEntity extends BaseEntity
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
     * @var string
     */
    public $twitter_id;

    /**
     * @var BaseEntityCollection[ContributeSummaryEntity]
     */
    public $contributes;

    /**
     * classを配列に変換
     * @return array
     */
    public function toArray()
    {

        return [
            'id' => $this->id,
            'name'  => $this->name,
            'twitter_id'  => $this->twitter_id,
            'contributes' => $this->contributes,
        ];
    }
}
