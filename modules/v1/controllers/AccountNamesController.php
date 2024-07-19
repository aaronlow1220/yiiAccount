<?php

namespace v1\controllers;

use Throwable;
use app\components\account\AccountRepo;
use v1\components\ActiveApiController;
use v1\components\account\AccountCreateService;
use v1\components\account\AccountSearchService;
use v1\components\account\AccountUpdateService;
use yii\base\InvalidArgumentException;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBearerAuth;
use yii\web\HttpException;

/**
 * @OA\Tag(
 *     name="AccountNames",
 *     description="Everything about your AccountNames",
 * )
 */
class AccountNamesController extends ActiveApiController
{
    /**
     * @var string
     */
    public $modelClass = 'app\models\AccountNames';

    /**
     * constructor.
     *
     * @param string $id
     * @param Module $module
     * @param AccountSearchService $accountSearchService
     * @param AccountCreateService $accountCreateService
     * @param AccountUpdateService $accountUpdateService
     * @param AccountRepo $accountRepo
     * @param array<string, mixed> $config
     * @return void
     */
    public function __construct($id, $module, private AccountSearchService $accountSearchService, private AccountCreateService $accountCreateService, private AccountUpdateService $accountUpdateService, private AccountRepo $accountRepo, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * behaviors.
     *
     * @return array<string, mixed>
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['search', 'new-record', 'update-record'],
        ];

        return $behaviors;
    }

    /**
     * {@inherit}.
     *
     * @return array<string, mixed>
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['dataFilter'] = [
            'class' => 'yii\data\ActiveDataFilter',
            'searchModel' => $this->modelClass,
        ];

        $actions['index']['pagination'] = [
            'class' => 'v1\components\Pagination',
        ];

        unset($actions['index']);

        return $actions;
    }

    /**
     * @OA\Post(
     *     path="v1/account-names/new-record",
     *     summary="Create new record",
     *     description="Create new record",
     *     operationId="newRecord",
     *     tags={"AccountNames"},
     *     @OA\RequestBody(
     *         description="Create new record",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *              @OA\Property(property="serial_number", type="string", description="Serial number of accounting", maxLength=16),
     *              @OA\Property(property="name", type="string", description="Chinese name of accounting item", maxLength=128),
     *              @OA\Property(property="en_name", type="string", description="English name of accounting item", maxLength=128),
     *              @OA\Property(property="parent_id", type="integer", description="ID of parent node", maxLength=10),
     *              @OA\Property(property="is_debit", type="string", enum={"0", "1"}, description="Debit or credit 0: credit 1: debit", maxLength=10),
     *              @OA\Property(property="type", type="string", description="accounting type", maxLength=128, default=""),
     *              @OA\Property(property="note", type="string", description="note", maxLength=128),
     *              @OA\Property(property="for_statement", type="string", enum={"0", "1"}, description="Whether statement is needed 0: no 1: yes", maxLength=10),
     *              @OA\Property(property="is_need_purchase_order", type="string", enum={"0", "1"}, description="Whether purchase order is needed 0: no 1: yes", maxLength=10),
     * )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AccountNames")
     *         )
     *     )
     * )
     *
     * Create new record
     *
     * @return ActiveRecord
     */
    public function actionNewRecord()
    {
        try {
            $params = $this->getRequestParams();

            return $this->accountCreateService->create($params);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * @OA\PATCH(
     *     path="v1/account-names/update-record/{id}",
     *     summary="Update record",
     *     description="Update record",
     *     operationId="updateRecord",
     *     tags={"AccountNames"},
     *     @OA\RequestBody(
     *         description="Update record",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *              @OA\Property(property="serial_number", type="string", description="Serial number of accounting", maxLength=16),
     *              @OA\Property(property="name", type="string", description="Chinese name of accounting item", maxLength=128),
     *              @OA\Property(property="en_name", type="string", description="English name of accounting item", maxLength=128),
     *              @OA\Property(property="parent_id", type="integer", description="ID of parent node", maxLength=10),
     *              @OA\Property(property="is_debit", type="string", enum={"0", "1"}, description="Debit or credit 0: credit 1: debit", maxLength=10),
     *              @OA\Property(property="type", type="string", description="accounting type", maxLength=128, default=""),
     *              @OA\Property(property="note", type="string", description="note", maxLength=128),
     *              @OA\Property(property="for_statement", type="string", enum={"0", "1"}, description="Whether statement is needed 0: no 1: yes", maxLength=10),
     *              @OA\Property(property="is_need_purchase_order", type="string", enum={"0", "1"}, description="Whether purchase order is needed 0: no 1: yes", maxLength=10),
     * )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AccountNames")
     *         )
     *     )
     * )
     *
     * Update record
     *
     * @param int $id Account ID
     *
     * @return ActiveRecord
     */
    public function actionUpdateRecord($id)
    {
        try {
            $params = $this->getRequestParams();
            $account = $this->accountRepo->getAccountById($id);

            return $this->accountUpdateService->update($account, $params);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * @OA\Post(
     *     path="v1/account-names/search",
     *     summary="Search",
     *     description="Search AccountNames by particular params",
     *     operationId="searchAccountNames",
     *     tags={"AccountNames"},
     *     @OA\RequestBody(
     *         description="search AccountNames",
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AccountSearch")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *              @OA\Property(property="_data", type="array", @OA\Items(ref="#/components/schemas/AccountNames")),
     *              @OA\Property(property="_meta", type="object", ref="#/components/schemas/Pagination")
     *             )
     *         )
     *     )
     * )
     *
     * Search AccountNames
     *
     * @return ActiveDataProvider
     */
    public function actionSearch(): ActiveDataProvider
    {
        try {
            $params = $this->getRequestParams();

            return $this->accountSearchService->createDataProvider($params);
        } catch (InvalidArgumentException $e) {
            throw new HttpException(400, $e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
