<?php

namespace v1\components\account;

use app\components\account\AccountRepo;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Account create service.
 */
class AccountSearchService
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
     * create search query.
     *
     * @param array<string, mixed> $params
     * @return Activerecord
     */
    public function createServiceProvider($params)
    {
        $transaction = $this->accountRepo->getDb()->beginTransaction();

        try {
            $user = $this->accountRepo->create($params);
            $transaction->commit();

            return $user;
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}
