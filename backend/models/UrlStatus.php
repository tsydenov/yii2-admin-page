<?php

namespace backend\models;

use yii\db\ActiveRecord;

class UrlStatus extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{url_status}}';
    }

    public function rules()
    {
        return [
            [['hash_string', 'created_at', 'updated_at', 'url'], 'required'],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            ['hash_string', 'string', 'length' => [1, 32]],
            ['url', 'string', 'length' => [1, 255]],
            [['status_code', 'query_count'], 'default', 'value' => null],
            [['status_code', 'query_count'], 'integer'],
            ['hash_string', 'unique'],
        ];
    }
}
