<?php

namespace app\components\account;

use app\components\enum\IsDebitEnum;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use AtelliTech\Yii2\Utils\AbstractRepository;
use app\models\Account;

class AccountRepo extends AbstractRepository
{
    /**
     * @var string the model class name. This property must be set.
     */
    protected string $modelClass = Account::class;

    /**
     * @OA\Get(
     *     path="/account",
     *     summary="Get all accounts",
     *     tags={"Account"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Account")
     *         )
     *     )
     * )
     */
    public function getAllAccounts()
    {
        return Account::find()->all();
    }

    public function getAccountById($id)
    {
        return Account::findOne($id);
    }

    public function getAccountBySerialNumber($serialNumber)
    {
        return Account::find()->where(['serial_number' => $serialNumber])->one();
    }

    public function getDebitAccounts()
    {
        return $this->find()->where(['is_debit' => IsDebitEnum::DEBIT()->getValue()])->all();
    }

    public function getCreditAccounts()
    {
        return $this->find()->where(['is_debit' => IsDebitEnum::CREDIT()->getValue()])->all();
    }
}
