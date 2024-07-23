<?php

namespace Unit;

use Codeception\Stub;
use Codeception\Test\Unit;
use app\components\account\AccountRepo;
use v1\components\account\AccountCreateService;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Transaction;

/**
 * @internal
 * @coversNothing
 */
class CreateAccountTest extends Unit
{
    protected $tester;
    protected $accountRepo;
    protected $service;

    public function testCreateWithParentId()
    {
        $params = [
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
            'name' => '現金及約當現金fb',
            'en_name' => 'Cash and Cash Equivsdfalentsfbqwe',
            'parent_id' => 1,
            'count' => 0,
            'level' => 2,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ];

        $parentAccount = ['id' => 1, 'count' => 5, 'level' => 1];

        $this->accountRepo->method('getAccountById')
            ->with(1)
            ->willReturn($parentAccount);

        $transaction = $this->createMock(Transaction::class);
        $transaction->expects($this->once())->method('commit');
        $transaction->expects($this->never())->method('rollBack');

        $this->accountRepo->method('getDb')
            ->willReturn(Stub::makeEmpty(Connection::class, ['beginTransaction' => $transaction]));

        $this->accountRepo->expects($this->once())
            ->method('update')
            ->with($parentAccount['id'], ['count' => $parentAccount['count'] + 1]);

        $this->accountRepo->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($accountParams) use ($params) {
                return $accountParams['name'] === $params['name']
                    && 0 === $accountParams['count']
                    && 2 === $accountParams['level'];
            }))
            ->willReturnCallback(function () use ($expected) {
                $accountMock = $this->createMock(ActiveRecord::class);
                $accountMock->method('toArray')->willReturn($expected);

                return $accountMock;
            });

        $result = $this->service->create($params);

        $this->assertEquals($expected, $result->toArray());
    }

    protected function _before()
    {
        $this->accountRepo = $this->createMock(AccountRepo::class);
        $this->service = new AccountCreateService($this->accountRepo);
    }
}
