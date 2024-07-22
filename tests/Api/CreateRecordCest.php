<?php


namespace Api;

use \ApiTester;

class CreateRecordCest
{
    public function createRecordViaApi(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/new-record', [
            "serial_number" => "71266",
            "name" => "現金及約當現金fb",
            "en_name" => "Cash and Cash Equivsdfalentsfbqwe",
            "parent_id" => 3,
            "is_debit" => "1",
            "type" => "流動資產",
            "note" => "統治大項科目",
            "for_statement" => "0",
            "is_need_purchase_order" => "0"
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "serial_number" => "71266",
            "name" => "現金及約當現金fb",
            "en_name" => "Cash and Cash Equivsdfalentsfbqwe",
            "parent_id" => "3",
            "is_debit" => "1",
            "type" => "流動資產",
            "note" => "統治大項科目",
            "for_statement" => "0",
            "is_need_purchase_order" => "0",
        ]);
    }
}
