<?php

namespace Api;

use ApiTester;

class UpdateRecordCest
{
    public function updateRecordViaApi(ApiTester $I)
    {
        $I->wantTo('Update a record via API');
        $I->sendPATCH('/update-record/545', [
            'name' => '現金及約當現金fb',
            'en_name' => 'Cash and Cash Equivsdfalentsfbqwe',
            'parent_id' => 1,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => '現金及約當現金fb',
            'en_name' => 'Cash and Cash Equivsdfalentsfbqwe',
            'parent_id' => 1,
            'is_debit' => '1',
            'type' => '流動資產',
            'note' => '統治大項科目',
            'for_statement' => '0',
            'is_need_purchase_order' => '0',
        ]);
    }
}
