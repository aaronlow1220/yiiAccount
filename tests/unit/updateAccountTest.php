<?php


namespace Unit;

use Codeception\Stub;
use \Codeception\Test\Unit;
use app\components\account\AccountRepo;
use v1\components\account\AccountUpdateService;
use yii\db\ActiveRecord;
use yii\db\Transaction;
use \yii\db\Connection;
use yii\db\Exception;


class updateAccountTest extends Unit
{
    protected $accountRepo;
    protected $service;

    // private function array2ActiveRecord(array $attributes)
    // {
    //     $activeRecord = $this->createMock(ActiveRecord::class);
    //     foreach ($attributes as $key => $value) {
    //         $activeRecord->$key = $value;
    //     }
    //     return $activeRecord;
    // }

    public function testUpdateWithParentId()
    {
        $account = [
            "id" => 6,
        ];

        $this->accountRepo->method('getAccountById')
            ->with(7)
            ->willReturn($account);
    }

    protected function _before()
    {
        $this->accountRepo = $this->createMock(AccountRepo::class);
        $this->service = new AccountUpdateService($this->accountRepo);
    }
}
