<?php

namespace App\Entities;

class ContributeSummaryEntity extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $post_count;

    /**
     * @var int
     */
    public $reaction_count;

    /**
     * @var string
     */
    public $date;

    /**
     * classを配列に変換
     * @return array
     */
    public function toArray()
    {
        return [
            'id'             => $this->id,
            'post_count'     => $this->post_count,
            'reaction_count' => $this->reaction_count,
            'date'           => $this->date,
        ];
    }


}
