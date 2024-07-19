<?php

namespace app\components\account;

use AtelliTech\Yii2\Utils\AbstractRepository;
use app\components\enum\IsDebitEnum;
use app\models\Account;
use yii\db\ActiveRecordInterface;

class AccountRepo extends AbstractRepository
{
    /**
     * @var string the model class name. This property must be set.
     */
    protected string $modelClass = Account::class;

    /**
     * Get all accounts.
     *
     * @return array<string, mixed>
     */
    public function getAllAccounts()
    {
        return Account::find()->all();
    }

    /**
     * Get account by id.
     *
     * @param int $id
     * @return null|Account
     */
    public function getAccountById($id)
    {
        return Account::findOne($id);
    }

    /**
     * Get account by serial number.
     *
     * @param string $serialNumber
     * @return null|ActiveRecordInterface
     */
    public function getAccountBySerialNumber($serialNumber)
    {
        return Account::find()->where(['serial_number' => $serialNumber])->one();
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
