<?php

namespace app\components\account;

use AtelliTech\Yii2\Utils\AbstractRepository;
use app\components\enum\IsDebitEnum;
use app\models\AccountNames;
use yii\db\ActiveRecordInterface;
use yii\db\ActiveRecord;

class AccountRepo extends AbstractRepository
{
    /**
     * @var string the model class name. This property must be set.
     */
    protected string $modelClass = AccountNames::class;

    /**
     * Get all accounts.
     *
     * @return array<string, mixed>
     */
    public function getAllAccounts()
    {
        return AccountNames::find()->all();
    }

    /**
     * Get account by id.
     *
     * @param int $id
     * @return null|ActiveRecord
     */
    public function getAccountById($id)
    {
        return AccountNames::findOne($id);
    }

    /**
     * Get account by serial number.
     *
     * @param string $serialNumber
     * @return null|ActiveRecordInterface
     */
    public function getAccountBySerialNumber($serialNumber)
    {
        return AccountNames::find()->where(['serial_number' => $serialNumber])->one();
    }

    /**
     * Get dedit accounts.
     *
     * @return array<string, mixed>
     */
    public function getDebitAccounts()
    {
        return $this->find()->where(['is_debit' => IsDebitEnum::DEBIT()->getValue()])->all();
    }

    /**
     * Get credit accounts.
     *
     * @return array<string, mixed>
     */
    public function getCreditAccounts()
    {
        return $this->find()->where(['is_debit' => IsDebitEnum::CREDIT()->getValue()])->all();
    }
}
