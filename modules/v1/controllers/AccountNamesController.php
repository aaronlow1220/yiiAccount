<?php

namespace v1\controllers;

use Throwable;
use v1\components\ActiveApiController;
use v1\components\account\AccountSearchService;
use yii\base\InvalidArgumentException;
use yii\base\Module;
use yii\data\ActiveDataProvider;
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
     * @param array<string, mixed> $config
     * @return void
     */
    public function __construct($id, $module, private AccountSearchService $accountSearchService, $config = [])
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
            'except' => ['search'],
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
     *              @OA\Property(property="_data", type="array", @OA\Items(ref="#/components/schemas/Account")),
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