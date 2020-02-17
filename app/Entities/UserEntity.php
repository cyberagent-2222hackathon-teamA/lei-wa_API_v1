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
    public $profile_image_url;

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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'twitter_profile_image' => $this->profile_image_url,
            'contributes'           => $this->contributes,
        ];
    }
}
