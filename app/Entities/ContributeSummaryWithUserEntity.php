<?php

namespace App\Entities;

class ContributeSummaryWithUserEntity extends BaseEntity
{

    /**
     * @var string
     */
    public $date;

    /**
     * @var int
     */
    public $post_count;

    /**
     * @var int
     */
    public $reaction_count;

    /**
     * @var UserEntity_sm
     */
    public $user;

    /**
     * classを配列に変換
     * @return array
     */
    public function toArray()
    {
        return [
            'date'           => $this->date,
            'post_count'     => $this->post_count,
            'reaction_count' => $this->reaction_count,
            'user'           => $this->user,
        ];
    }


}
