<?php

namespace App\Utilities;

use App\Entities\BaseEntityCollection;
use JsonMapper;

class EntityMapper
{
    /**
     * @var JsonMapper
     */
    private static $jm;

    /**
     * initialize
     */
    public static function init(): void
    {
        $jm = new JsonMapper();

        $jm->bEnforceMapType  = false;
        $jm->bStrictNullTypes = false;

        self::$jm = $jm;
    }

    /**
     * jsonmapperを使って、配列を一気にEntityにマッピングする
     *
     * @param mixed $data
     * @param string $class クラス名
     * @throws
     * @return mixed
     */
    public static function map($data, string $class)
    {
        return self::$jm->map($data ?? [], new $class);
    }

    /**
     * ↑のcollectionでラップする版
     *
     * @param array $data
     * @param string $class クラス名
     * @throws
     * @return BaseEntityCollection
     */
    public static function collection(array $data, string $class)
    {
        $entities = [];

        foreach ($data as $d) {
            $entities[] = self::$jm->map($d, new $class);
        }

        return new BaseEntityCollection($entities);
    }

    /**
     * new済みのクラスに追加マッピングする
     *
     * @param mixed $data
     * @param mixed $class new済みのクラス
     * @throws
     * @return mixed
     */
    public static function append($data, $class)
    {
        return self::$jm->map($data, $class);
    }
}

EntityMapper::init();
