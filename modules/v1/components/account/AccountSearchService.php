<?php

namespace v1\components\account;

use app\components\account\AccountRepo;
use yii\data\ActiveDataProvider;

class AccountSearchService
{
    public function __construct(private AccountRepo $accountRepo)
    {
    }

    public function createDataProvider($params, $currentUser): ActiveDataProvider
    {
        $dataProvider = new ActiveDataProvider([
            'query' => &$query,
            'pagination' => [
                'class' => 'v1\components\Pagination',
                'params' => $params,
            ],
            'sort' => [
                'enableMultiSort' => true,
                'params' => $params,
            ],
        ]);

        return $dataProvider;
    }
}