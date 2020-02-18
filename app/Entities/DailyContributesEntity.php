<?php

namespace App\Entities;

class DailyContributesEntity extends BaseEntity
{

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $text;

    /**
     * @var BaseEntityCollection[ReactionEntity]
     */
    public $reactions;

    /**
     * classを配列に変換
     * @return array
     */
    public function toArray()
    {

        return [
            'id'        => $this->id,
            'message'   => $this->text,
            'reactions' => $this->reactions ? $this->reactions : [],
        ];
    }
}
