<?php

namespace Unit;

use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use app\components\account\AccountRepo;
use v1\components\account\AccountUpdateService;
use yii\db\Connection;
use yii\db\Transaction;

/**
 * @internal
 * @coversNothing
 */
class UpdateAccountTest extends Unit
{
    protected $accountRepo;
    protected $service;

    public function unsetTime($array)
    {
        unset($array['created_at'], $array['updated_at']);

        return $array;
    }

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
        $this->accountRepo = Stub::make(AccountRepo::class, [
            'getAccountById' => function ($id) use ($parentAccount, $newParentAccount) {
                if (16 === $id) {
                    return $parentAccount;
                }
                if (3 === $id) {
                    return $newParentAccount;
                }

                return null;
            },
            'update' => function ($id, $params) use ($expected) {
                if (1 === $id) {
                    return $expected;
                }

                return null;
            },
            'getDb' => Stub::make(Connection::class, [
                'beginTransaction' => Stub::make(Transaction::class, [
                    'commit' => Expected::once(),
                    'rollBack' => Expected::never(),
                ]),
            ]),
        ]);

        $result = $this->unsetTime($this->service->update($account, $params));

        $this->assertEquals($expected, $result->toArray());
    }

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

        $this->accountRepo = Stub::make(AccountRepo::class, [
            'getAccountById' => function ($id) use ($parentAccount) {
                if (2 === $id) {
                    return $parentAccount;
                }

                return null;
            },
            'update' => function ($id, $params) use ($expected) {
                if (1 === $id) {
                    return $expected;
                }

                return null;
            },
            'getDb' => Stub::make(Connection::class, [
                'beginTransaction' => Stub::make(Transaction::class, [
                    'commit' => Expected::once(),
                    'rollBack' => Expected::never(),
                ]),
            ]),
        ]);

        $result = $this->unsetTime($this->service->update($account, $params));

        $this->assertEquals($expected, $result->toArray());
    }

    protected function _before()
    {
        $this->accountRepo = Stub::make(AccountRepo::class);
        $this->service = new AccountUpdateService($this->accountRepo);
    }
}
