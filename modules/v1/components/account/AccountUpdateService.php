<?php

namespace v1\components\account;

use Throwable;
use app\components\account\AccountRepo;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Account update service.
 */
class AccountUpdateService
{
    /**
     * construct.
     *
     * @param AccountRepo $accountRepo
     * @return void
     */
    public function __construct(private AccountRepo $accountRepo) {}

    /**
     * Update an account.
     *
     * @param ActiveRecord $account
     * @param array<string, mixed> $params
     * @return ActiveRecord
     */
    public function update($account, $params)
    {
        $parentIdInParams = isset($params['parent_id']);
        $parent = $this->accountRepo->getAccountById($account['parent_id']);
        $parentCount = ['count' => $parent['count']];
        $newParent = null;

        // With parent_id in params
        if ($parentIdInParams) {
            if ($params['parent_id'] == $account['id']) {
                throw new HttpException(403, 'Cannot set parent_id to self');
            }
            if (0 != $account['count']) {
                throw new HttpException(403, 'Child node exists, cannot change parent_id');
            }
            $newParent = $this->accountRepo->getAccountById($params['parent_id']);
            --$parentCount['count'];
        }

        $transaction = $this->accountRepo->getDb()->beginTransaction();

        try {
            if ($parentIdInParams) {
                $params['level'] = $newParent['level'] + 1;
                $this->accountRepo->update($newParent['id'], ['count' => $newParent['count'] + 1]);
            }

            $account = $this->accountRepo->update($account['id'], $params);
            $transaction->commit();

            return $account;
        } catch (Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}
