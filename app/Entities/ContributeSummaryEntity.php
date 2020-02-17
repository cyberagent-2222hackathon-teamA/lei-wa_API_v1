<?php

namespace App\Entities;

class ContributeSummaryEntity extends BaseEntity
{
    /**
     * @var int
     */
    public $count;

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
            'count' => $this->count,
            'date'  => $this->date,
        ];
    }
}
