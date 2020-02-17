<?php

namespace App\Entities;

class DailyContributesEntity extends BaseEntity
{

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
            'message'   => $this->text,
            'reactions' => $this->reactions ? $this->reactions : [],
        ];
    }
}
