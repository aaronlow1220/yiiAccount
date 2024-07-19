<?php

namespace v1\models\validator;

use app\components\enum\IsDebitEnum;

/**
 * Account search model which supports the search with keyword.
 *
 *  @OA\Schema(
 *   schema="AccountSearch",
 *   oneOf={
 *      @OA\Schema(ref="#/components/schemas/ApiSearchModel"),
 *   }
 * )
 */
class AccountSearch extends ApiSearchModel
{
    /**
     * @var null|string keyword for search
     * @OA\Property(default=null)
     */
    public $keyword;

    /**
     * @var null|string[] debit values for search
     * @OA\Property(type="array", @OA\Items(ref="#/components/schemas/AccountNames/properties/is_debit"), default=null)
     */
    public $IsDebitValue;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [['keyword'], 'string'];
        $rules[] = [['IsDebitValue'], 'each', 'rule' => ['in', 'range' => IsDebitEnum::toArray()]];

        return $rules;
    }
}
