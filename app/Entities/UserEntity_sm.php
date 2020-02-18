<?php

namespace App\Entities;

class UserEntity_sm extends BaseEntity
{

    /**
     * @var integer
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
     * classを配列に変換
     * @return array
     */
    public function toArray()
    {

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'twitter_profile_image' => $this->profile_image_url,
        ];
    }
}
