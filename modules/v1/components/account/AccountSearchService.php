<?php

namespace v1\components\account;

use app\components\account\AccountRepo;
use v1\models\validator\AccountSearch;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;

/**
 * Account search service.
 */
class AccountSearchService
{
    /**
     * construct.
     *
     * @param AccountRepo $accountRepo
     */
    public function __construct(private AccountRepo $accountRepo) {}

    /**
     * create search query.
     *
     * @param array<string, mixed> $params
     * @return ActiveDataProvider
     */
    public function createDataProvider($params): ActiveDataProvider
    {
        $searchModel = new AccountSearch($params);

        if (!$searchModel->validate()) {
            throw new InvalidArgumentException(implode(' ', $searchModel->getErrorSummary(true)));
        }

        $query = $this->accountRepo->find();

        if ($searchModel->keyword) {
            $query->andFilterWhere(['or',
                ['like', 'name', $searchModel->keyword],
                ['like', 'en_name', $searchModel->keyword],
                ['like', 'type', $searchModel->keyword],
                ['like', 'note', $searchModel->keyword],
            ]);
        }

        if ($searchModel->IsDebitValue) {
            $query->andFilterWhere(['is_debit'=> $searchModel->IsDebitValue]);
        }

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
