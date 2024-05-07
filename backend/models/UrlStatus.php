<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class UrlStatus extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{url_status}}';
    }

    public function rules()
    {
        return [
            [['hash_string', 'url'], 'required'],
            ['hash_string', 'string', 'length' => [1, 32]],
            ['url', 'string', 'length' => [1, 255]],
            [['status_code', 'query_count'], 'default', 'value' => null],
            [['status_code', 'query_count'], 'integer'],
            ['hash_string', 'unique'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_INSERT => ['created_at', 'updated_at']
                    // updated_at attribute will be changed manually via touch() method when updating a record
                ]
            ]
        ];
    }
}
