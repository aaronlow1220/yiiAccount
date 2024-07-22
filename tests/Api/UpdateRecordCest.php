<?php


namespace Api;

use \ApiTester;

class UpdateRecordCest
{
    public function updateRecordViaApi(ApiTester $I)
    {
        $I->wantTo('Update a record via API');
        $I->sendPATCH('/update-record/1110', [
            'name' => '現金',
            'en_name' => 'Cash',
            'is_debit' => '1',
            'type' => 'Test Type',
            'note' => 'Test Note',
            'for_statement' => '1',
            'is_need_purchase_order' => '1',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Test Name',
            'en_name' => 'Test English Name',
            'parent_id' => 0,
            'is_debit' => '1',
            'type' => 'Test Type',
            'note' => 'Test Note',
            'for_statement' => '1',
            'is_need_purchase_order' => '1',
        ]);
    }
}
