<?php

namespace Unit;

use Codeception\Test\Unit;
use app\components\account\AccountRepo;

/**
 * @internal
 * @coversNothing
 */
class DeleteAccountTest extends Unit
{
    /**
     * @var AccountRepo
     */
    protected $accountRepo;

    /**
     * @var
     */
    protected $service;

    /**
     * Test delete account success.
     */
    public function testDeleteSuccess() {}

    /**
     * Test delete account fail.
     */
    public function testDeleteFail() {}

    /*
     * before test.
     */
    protected function _before()
    {
        $this->accountRepo = $this->make(AccountRepo::class);
    }
}
