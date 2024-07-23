<?php

namespace Unit;

use Codeception\Test\Unit;
use Codeception\Stub;
use app\components\account\AccountRepo;
use v1\components\account\AccountCreateService;
use yii\db\ActiveRecord;

class CreateAccountTest extends Unit
{
    protected $tester;
    protected $accountRepo;
    protected $service;

    protected function _before()
    {
        $this->accountRepo = $this->createMock(AccountRepo::class);
        $this->service = new AccountCreateService($this->accountRepo);
    }

    public function testCreateWithParentId()
    {
        $params = [
            "name" => "現金及約當現金fb",
            "en_name" => "Cash and Cash Equivsdfalentsfbqwe",
            "parent_id" => 1,
            "is_debit" => "1",
            "type" => "流動資產",
            "note" => "統治大項科目",
            "for_statement" => "0",
            "is_need_purchase_order" => "0"
        ];

        $expected = [
            "name" => "現金及約當現金fb",
            "en_name" => "Cash and Cash Equivsdfalentsfbqwe",
            "parent_id" => 1,
            "count" => 0,
            "level" => 2,
            "is_debit" => "1",
            "type" => "流動資產",
            "note" => "統治大項科目",
            "for_statement" => "0",
            "is_need_purchase_order" => "0"
        ];

        $parentAccount = ['id' => 1, 'count' => 5, 'level' => 1];

        $this->accountRepo->method('getAccountById')
            ->with(1)
            ->willReturn($parentAccount);

        $transaction = $this->createMock(\yii\db\Transaction::class);
        $transaction->expects($this->once())->method('commit');
        $transaction->expects($this->never())->method('rollBack');

        $this->accountRepo->method('getDb')
            ->willReturn(Stub::makeEmpty(\yii\db\Connection::class, ['beginTransaction' => $transaction]));

        $this->accountRepo->expects($this->once())
            ->method('update')
            ->with($parentAccount['id'], ['count' => $parentAccount['count'] + 1]);

        $this->accountRepo->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($accountParams) use ($params) {
                return $accountParams['name'] === $params['name'] &&
                    $accountParams['count'] === 0 &&
                    $accountParams['level'] === 2;
            }))
            ->willReturnCallback(function () use ($expected) {
                $accountMock = $this->createMock(ActiveRecord::class);
                $accountMock->method('toArray')->willReturn($expected);
                return $accountMock;
            });

        $result = $this->service->create($params);

        $this->assertEquals($expected, $result->toArray());
    }
}
