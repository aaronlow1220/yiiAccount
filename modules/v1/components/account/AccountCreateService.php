<?php

namespace v1\components\account;

use app\components\account\AccountRepo;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Account create service.
 */
class AccountCreateService
{
    /**
     * construct.
     *
     * @param AccountRepo $accountRepo
     */
    public function __construct(private AccountRepo $accountRepo) {}

    /**
     * create search query.
     *
     * @param array<string, mixed> $params
     * @return ActiveRecord
     */
    public function create($params)
    {
        $parentCount = 0;
        $parent = null;
        $level = 1;
        if (isset($params['parent_id']) && 0 != $params['parent_id']) {
            $parent = $this->accountRepo->getAccountById($params['parent_id']);
            $parentCount = ['count' => $parent['count'] + 1];
            $level = $parent['level'] + 1;
        }

        $transaction = $this->accountRepo->getDb()->beginTransaction();

        try {
            if ($parent) {
                $this->accountRepo->update($parent['id'], $parentCount);
            }
            $params['count'] = 0;
            $params['level'] = $level;
            $account = $this->accountRepo->create($params);
            $transaction->commit();

            return $account;
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}
