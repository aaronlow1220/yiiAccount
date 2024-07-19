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
        $parent = $this->accountRepo->getAccountById($account['parent_id']);
        $newParent = $this->accountRepo->getAccountById($params['parent_id']);
        $count = ['count' => $parent['count']];
        if (isset($params['parent_id']) && 0 != $account['count']) {
            throw new HttpException(403, 'Cannot change parent_id when count is not 0');
        }

        if (isset($params['parent_id']) && $params['parent_id'] == $account['id']) {
            throw new HttpException(403, 'Cannot set parent_id to self');
        }

        $transaction = $this->accountRepo->getDb()->beginTransaction();

        try {
            if (isset($params['parent_id']) && $params['parent_id'] != $account['parent_id']) {
                --$count['count'];
                $params['level'] = $newParent['level'] + 1;
                $this->accountRepo->update($newParent['id'], ['count' => $newParent['count'] + 1]);
            }
            $this->accountRepo->update($parent['id'], $count);
            $account = $this->accountRepo->update($account['id'], $params);
            $transaction->commit();

            return $account;
        } catch (Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}
