<?php

namespace v1\components\account;

use app\components\account\AccountRepo;
use Throwable;
use yii\db\ActiveRecord;

class AccountCreateService
{
    /**
     * construct.
     *
     * @param AccountRepo $accountRepo
     */
    public function __construct(private AccountRepo $accountRepo)
    {
    }

    /**
     * create account.
     *
     * @param array<string, mixed> $params
     * @return ActiveRecord
     */
    public function create($params)
    {
        $transaction = $this->accountRepo->getDb()->beginTransaction();

        try {
            $account = $this->accountRepo->create($params);
            $transaction->commit();
            return $account;

        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}