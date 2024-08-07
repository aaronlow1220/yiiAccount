<?php

namespace Unit;

use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;
use app\components\account\AccountRepo;
use v1\components\account\AccountUpdateService;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Transaction;

/**
 * @internal
 * @coversNothing
 */
class UpdateAccountTest extends Unit
{
    /**
     * @var AccountRepo
     */
    protected $accountRepo;

    /**
     * @var AccountUpdateService
     */
    protected $service;

    /**
     * Unset attributes from array or ActiveRecord.
     *
     * To not compare attributes that are not needed.
     * For example: id, created_at, updated_at.
     * They dynamically change and are not needed for comparison.
     *
     * @param ActiveRecord|array<string, mixed> $array
     * @param array<string, mixed> $attributes
     * @return ActiveRecord|array<string, mixed>
     */
    public function unsetAttributes($array, $attributes)
    {
        foreach ($attributes as $attribute) {
            unset($array[$attribute]);
        }

        return $array;
    }

    /**
     * Test update account success with parent id.
     */
    public function testUpdateSuccessWithParentId()
    {
        $params = [
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'parent_id' => 3,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => 'test',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $account = [
            'id' => 18,
            'serial_number' => '114002',
            'name' => '備供出售金融資產評價調整-流動',
            'en_name' => 'Adjustments for change in value of financial assets in available-for-sale-current',
            'parent_id' => 16,
            'count' => 0,
            'level' => 4,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $parentAccount = [
            'id' => 16,
            'serial_number' => '1140',
            'name' => '備供出售金融資產-流動',
            'en_name' => 'Financial assets in available-for-sale-current',
            'parent_id' => 2,
            'count' => 3,
            'level' => 3,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $newParentAccount = [
            'id' => 3,
            'serial_number' => '1110-1120',
            'name' => '現金及約當現金',
            'en_name' => 'Cash and Cash Equivalents',
            'parent_id' => 2,
            'count' => 0,
            'level' => 3,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $expected = [
            'id' => 18,
            'serial_number' => '114002',
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'parent_id' => 3,
            'count' => 0,
            'level' => 4,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => 'test',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        // Stub the AccountRepo class functions used.
        $this->accountRepo = Stub::make(AccountRepo::class, [
            // To check for return of getAccountById to return parent account and new parent account. 16 is the old parent_id and 3 is the new parent_id.
            'getAccountById' => function ($id) use ($parentAccount, $newParentAccount) {
                if (16 === $id) {
                    return $parentAccount;
                }
                if (3 === $id) {
                    return $newParentAccount;
                }

                return null;
            },
            // To check for commit and rollback. Commit should be called once and rollback should not be called.
            'getDb' => Stub::make(Connection::class, [
                'beginTransaction' => Stub::make(Transaction::class, [
                    'commit' => Expected::once(),
                    'rollBack' => Expected::never(),
                ]),
            ]),
        ]);

        $result = $this->unsetAttributes($this->service->update($account, $params), ['created_at', 'updated_at']);

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * Test update account success with previous parent_id.
     */
    public function testUpdateWithoutParentId()
    {
        $params = [
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => 'test',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $account = [
            'id' => 20,
            'serial_number' => '1150',
            'name' => '以成本衡量之金融資產-流動',
            'en_name' => 'Financial assets at cost-current',
            'parent_id' => 2,
            'count' => 2,
            'level' => 3,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $parentAccount = [
            'id' => 2,
            'serial_number' => '11-12',
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'parent_id' => 1,
            'count' => 17,
            'level' => 2,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $expected = [
            'id' => 20,
            'serial_number' => '1150',
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'parent_id' => 2,
            'count' => 2,
            'level' => 3,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => 'test',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        // Stub the AccountRepo class functions used.
        $this->accountRepo = Stub::make(AccountRepo::class, [
            // To check for return of getAccountById to return parent account.
            'getAccountById' => function ($id) use ($parentAccount) {
                if (2 === $id) {
                    return $parentAccount;
                }

                return null;
            },
            // To check for commit and rollback. Commit should be called once and rollback should not be called.
            'getDb' => Stub::make(Connection::class, [
                'beginTransaction' => Stub::make(Transaction::class, [
                    'commit' => Expected::once(),
                    'rollBack' => Expected::never(),
                ]),
            ]),
        ]);

        $result = $this->unsetAttributes($this->service->update($account, $params), ['created_at', 'updated_at']);

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * Test update account throw exception when child node exist.
     */
    public function testUpdateThrowException()
    {
        $params = [
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'is_debit' => '1',
            'parent_id' => 3,
            'type' => '流動資產',
            'note' => 'test',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $account = [
            'id' => 20,
            'serial_number' => '1150',
            'name' => '以成本衡量之金融資產-流動',
            'en_name' => 'Financial assets at cost-current',
            'parent_id' => 2,
            'count' => 2,
            'level' => 3,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $parentAccount = [
            'id' => 2,
            'serial_number' => '11-12',
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'parent_id' => 1,
            'count' => 17,
            'level' => 2,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $expected = [
            'id' => 20,
            'serial_number' => '1150',
            'name' => '流動資產',
            'en_name' => 'Current assets',
            'parent_id' => 2,
            'count' => 2,
            'level' => 3,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => 'test',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        // Stub the AccountRepo class functions used.
        $this->accountRepo = Stub::make(AccountRepo::class, [
            // To check for return of getAccountById to return parent account.
            'getAccountById' => function ($id) use ($parentAccount) {
                if (2 === $id) {
                    return $parentAccount;
                }

                return null;
            },
            // To check for commit and rollback. Commit should not be called and rollback should be called once.
            'getDb' => Stub::make(Connection::class, [
                'beginTransaction' => Stub::make(Transaction::class, [
                    'commit' => Expected::never(),
                    'rollBack' => Expected::once(),
                ]),
            ]),
        ]);

        // Expect exception to be thrown with message.
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Child node exists, cannot change parent_id');

        $result = $this->service->update($account, $params);
    }

    /**
     * before test.
     */
    protected function _before()
    {
        $this->accountRepo = Stub::make(AccountRepo::class);
        $this->service = new AccountUpdateService($this->accountRepo);
    }
}
