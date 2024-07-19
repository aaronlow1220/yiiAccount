<?php

use yii\db\Migration;

/**
 * Class m240719_021112_insert_batch_data_from_account_names_sql
 */
class m240719_021112_insert_batch_data_from_account_names_sql extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sqlFilePath = dirname(__DIR__, 1) . '/account_names.sql';

        if (file_exists($sqlFilePath)) {
            $sqlContent = file_get_contents($sqlFilePath);
            $this->execute($sqlContent);
        } else {
            echo "SQL file not found: $sqlFilePath\n";
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240719_021112_insert_batch_data_from_account_names_sql cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240719_021112_insert_batch_data_from_account_names_sql cannot be reverted.\n";

        return false;
    }
    */
}
