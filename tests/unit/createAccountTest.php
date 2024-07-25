<?php

namespace Unit;

use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;
use app\components\account\AccountRepo;
use v1\components\account\AccountCreateService;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Transaction;

/**
 * Test for AccountCreateService.
 *
 * @internal
 * @coversNothing
 */
class CreateAccountTest extends Unit
{
    /**
     * @var AccountRepo
     */
    protected $accountRepo;

    /**
     * @var AccountCreateService
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
     * Test create account with parent id.
     */
    public function testCreateWithParentId()
    {
        $params = [
            'serial_number' => '225013',
            'name' => '現金及約當現金fb',
            'en_name' => 'Cash and Cash Equivsdfalentsfbqwe',
            'parent_id' => 1,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $expected = [
            'serial_number' => '225013',
            'name' => '現金及約當現金fb',
            'en_name' => 'Cash and Cash Equivsdfalentsfbqwe',
            'parent_id' => '1',
            'count' => 0,
            'level' => 2,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $parentAccount = ['id' => 1, 'count' => 5, 'level' => 1];

        // Stub the AccountRepo class functions used.
        $this->accountRepo = Stub::make(AccountRepo::class, [
            'getAccountById' => function ($id) use ($parentAccount) {
                if (1 === $id) {
                    return $parentAccount;
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

        $result = $this->service->create($params);
        $this->unsetAttributes($result, ['id', 'created_at', 'updated_at']);

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * Test create account without parent id, default to parent_id 0.
     */
    public function testCreateWithoutParentId()
    {
        $params = [
            'serial_number' => '225013',
            'name' => '現金及約當現金fb',
            'en_name' => 'Cash and Cash Equivsdfalentsfbqwe',
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $expected = [
            'serial_number' => '225013',
            'name' => '現金及約當現金fb',
            'en_name' => 'Cash and Cash Equivsdfalentsfbqwe',
            'parent_id' => 0,
            'count' => 0,
            'level' => 1,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        // Stub the AccountRepo class functions used.
        $this->accountRepo = Stub::make(AccountRepo::class, [
            'getDb' => Stub::make(Connection::class, [
                'beginTransaction' => Stub::make(Transaction::class, [
                    'commit' => Expected::once(),
                    'rollBack' => Expected::never(),
                ]),
            ]),
        ]);

        $result = $this->service->create($params);
        $this->unsetAttributes($result, ['id', 'created_at', 'updated_at']);

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * Test create account to throw exception with missing field.
     */
    public function testCreateThrowException()
    {
        $params = ['parent_id' => 1, 'name' => 'Child Account'];

        $parentAccount = ['id' => 1, 'count' => 5, 'level' => 1];

        // Stub the AccountRepo class functions used.
        $this->accountRepo = Stub::make(AccountRepo::class, [
            'getAccountById' => function ($id) use ($parentAccount) {
                if (1 === $id) {
                    return $parentAccount;
                }

                return null;
            },
            'getDb' => Stub::make(Connection::class, [
                'beginTransaction' => Stub::make(Transaction::class, [
                    'commit' => Expected::never(),
                    'rollBack' => Expected::once(),
                ]),
            ]),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Serial Number cannot be blank. En Name cannot be blank. Type cannot be blank. Note cannot be blank.');

        $this->service->create($params);
    }

    /*
     * before test.
     */
    protected function _before()
    {
        $this->accountRepo = $this->make(AccountRepo::class);
        $this->service = new AccountCreateService($this->accountRepo);
    }
}
