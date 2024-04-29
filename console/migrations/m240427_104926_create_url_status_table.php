<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%url_status}}`.
 */
class m240427_104926_create_url_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{url_status}}', [
            'hash_string' => $this->string(32)->unique()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'url' => $this->string(255)->notNull(),
            'status_code' => $this->integer()->defaultValue(null),
            'query_count' => $this->integer()->defaultValue(null),
        ]);

        $this->addPrimaryKey('PK_url_status_hash_string', '{{url_status}}', '[[hash_string]]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{url_status}}');
    }
}
