<?php

use yii\db\Migration;

/**
 * Class m240718_060607_create_account_names
 */
class m240718_060607_create_account_names extends Migration
{

    /**
     * @var string
     */
    private $tableName = 'account_names';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey()->unsigned()->notNull()->comment('編號'),
            'serial_number' => $this->string(16)->notNull()->comment('會計項目編號'),
            'name' => $this->string(128)->notNull()->comment('會計項目名稱'),
            'en_name' => $this->string(128)->notNull()->comment('會計項目英文名稱'),
            'parent_id' => $this->integer(10)->unsigned()->defaultValue(0)->comment('母項目 id'),
            'count' => $this->integer(10)->unsigned()->notNull()->defaultValue(0)->comment('單層子項目數量'),
            'level' => $this->tinyInteger(10)->unsigned()->notNull()->defaultValue(1)->comment('層級'),
            'is_debit' => 'ENUM("0", "1") NOT NULL DEFAULT "1" COMMENT "0: 貸, 1: 借"',
            'type' => $this->string(128)->notNull()->comment('資料類型'),
            'note' => $this->string(128)->notNull()->comment('摘要'),
            'created_at' => $this->integer(10)->unsigned()->notNull()->comment('unixtime'),
            'updated_at' => $this->integer(10)->unsigned()->notNull()->comment('unixtime'),
        ]);

        $this->createIndex('INDEX_SERIAL_NUMBER', $this->tableName, 'serial_number');
        $this->createIndex('INDEX_PARENT_ID', $this->tableName, 'parent_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240718_060607_create_account_names cannot be reverted.\n";

        return false;
    }
}
