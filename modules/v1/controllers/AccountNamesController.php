<?php

namespace v1\controllers;

use Throwable;
use v1\components\ActiveApiController;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use v1\components\account\AccountCreateService;

/**
 * @OA\Tag(
 *     name="AccountNames",
 *     description="Everything about your AccountNames",
 * )
 *
 * @OA\Get(
 *     path="/account-names",
 *     summary="List",
 *     description="List all AccountNames",
 *     operationId="listAccountNames",
 *     tags={"AccountNames"},
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         @OA\Schema(ref="#/components/schemas/StandardParams/properties/page")
 *     ),
 *     @OA\Parameter(
 *         name="pageSize",
 *         in="query",
 *         @OA\Schema(ref="#/components/schemas/StandardParams/properties/pageSize")
 *     ),
 *     @OA\Parameter(
 *         name="sort",
 *         in="query",
 *         @OA\Schema(ref="#/components/schemas/StandardParams/properties/sort")
 *     ),
 *     @OA\Parameter(
 *         name="fields",
 *         in="query",
 *         @OA\Schema(ref="#/components/schemas/StandardParams/properties/fields")
 *     ),
 *     @OA\Parameter(
 *         name="expand",
 *         in="query",
 *         @OA\Schema(type="string", enum={"xxxx"}, description="Query related models, using comma(,) be seperator")
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
 * @OA\Get(
 *     path="/account-names/{id}",
 *     summary="Get",
 *     description="Get AccountNames by particular id",
 *     operationId="getAccountNames",
 *     tags={"AccountNames"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="AccountNames id",
 *         required=true,
 *         @OA\Schema(ref="#/components/schemas/AccountNames/properties/id")
 *     ),
 *     @OA\Parameter(
 *         name="fields",
 *         in="query",
 *         @OA\Schema(ref="#/components/schemas/StandardParams/properties/fields")
 *     ),
 *     @OA\Parameter(
 *         name="expand",
 *         in="query",
 *         @OA\Schema(type="string", enum={"xxxx"}, description="Query related models, using comma(,) be seperator")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", ref="#/components/schemas/AccountNames")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/account-names",
 *     summary="Create",
 *     description="Create a record of AccountNames",
 *     operationId="createAccountNames",
 *     tags={"AccountNames"},
 *     @OA\RequestBody(
 *         description="AccountNames object that needs to be added",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  @OA\Property(property="id", ref="#/components/schemas/AccountNames/properties/id"),
 *                  @OA\Property(property="serial_number", ref="#/components/schemas/AccountNames/properties/serial_number"),
 *                  @OA\Property(property="name", ref="#/components/schemas/AccountNames/properties/name"),
 *                  @OA\Property(property="en_name", ref="#/components/schemas/AccountNames/properties/en_name"),
 *                  @OA\Property(property="parent_id", ref="#/components/schemas/AccountNames/properties/parent_id"),
 *                  @OA\Property(property="count", ref="#/components/schemas/AccountNames/properties/count"),
 *                  @OA\Property(property="level", ref="#/components/schemas/AccountNames/properties/level"),
 *                  @OA\Property(property="is_debit", ref="#/components/schemas/AccountNames/properties/is_debit"),
 *                  @OA\Property(property="type", ref="#/components/schemas/AccountNames/properties/type"),
 *                  @OA\Property(property="note", ref="#/components/schemas/AccountNames/properties/note"),
 *                  @OA\Property(property="created_at", ref="#/components/schemas/AccountNames/properties/created_at"),
 *                  @OA\Property(property="updated_at", ref="#/components/schemas/AccountNames/properties/updated_at")
 *             )
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", ref="#/components/schemas/AccountNames")
 *     )
 * )
 *
 * @OA\Patch(
 *     path="/account-names/{id}",
 *     summary="Update",
 *     description="Update a record of AccountNames",
 *     operationId="updateAccountNames",
 *     tags={"AccountNames"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="AccountNames id",
 *         required=true,
 *         @OA\Schema(ref="#/components/schemas/AccountNames/properties/id")
 *     ),
 *     @OA\RequestBody(
 *         description="AccountNames object that needs to be updated",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  @OA\Property(property="id", ref="#/components/schemas/AccountNames/properties/id"),
 *                  @OA\Property(property="serial_number", ref="#/components/schemas/AccountNames/properties/serial_number"),
 *                  @OA\Property(property="name", ref="#/components/schemas/AccountNames/properties/name"),
 *                  @OA\Property(property="en_name", ref="#/components/schemas/AccountNames/properties/en_name"),
 *                  @OA\Property(property="parent_id", ref="#/components/schemas/AccountNames/properties/parent_id"),
 *                  @OA\Property(property="count", ref="#/components/schemas/AccountNames/properties/count"),
 *                  @OA\Property(property="level", ref="#/components/schemas/AccountNames/properties/level"),
 *                  @OA\Property(property="is_debit", ref="#/components/schemas/AccountNames/properties/is_debit"),
 *                  @OA\Property(property="type", ref="#/components/schemas/AccountNames/properties/type"),
 *                  @OA\Property(property="note", ref="#/components/schemas/AccountNames/properties/note"),
 *                  @OA\Property(property="created_at", ref="#/components/schemas/AccountNames/properties/created_at"),
 *                  @OA\Property(property="updated_at", ref="#/components/schemas/AccountNames/properties/updated_at")
 *             )
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", ref="#/components/schemas/AccountNames")
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/account-names/{id}",
 *     summary="Delete",
 *     description="Delete a record of AccountNames",
 *     operationId="deleteAccountNames",
 *     tags={"AccountNames"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="AccountNames id",
 *         required=true,
 *         @OA\Schema(ref="#/components/schemas/AccountNames/properties/id")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 *
 * @version 1.0.0
 */
class AccountNamesController extends ActiveApiController
{
    /**
     * @var string $modelClass
     */
    public $modelClass = 'app\models\AccountNames';

    public function __construct($id, $module, private AccountCreateService $accountCreateService, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inherit}
     *
     * @return array<string, mixed>
     */
    public function actions()
    {
        $actions = parent::actions();

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['dataFilter'] = [
            'class' => 'yii\data\ActiveDataFilter',
            'searchModel' => $this->modelClass
        ];

        $actions['index']['pagination'] = [
            'class' => 'v1\components\Pagination'
        ];

        unset($actions['index']);

        return $actions;
    }

    /**
     * @OA\Post(
     *     path="/account-names/search",
     *     summary="Search",
     *     description="Search AccountNames by particular params",
     *     operationId="searchAccountNames",
     *     tags={"AccountNames"},
     *     @OA\RequestBody(
     *         description="search AccountNames",
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/xxxxxSearchModel")
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
     * @param xxxxxService $service
     * @return ActiveDataProvider
     */
    // public function actionSearch(xxxxxService $service): ActiveDataProvider
    // {
    //     try {
    //         $params = $this->getRequestParams();
    //         $query = $service->createSearchQuery($params);

    //         return new ActiveDataProvider([
    //             'query' => &$query,
    //             'pagination' => [
    //                 'class' => 'v1\components\Pagination',
    //                 'params' => $params
    //             ],
    //             'sort' => [
    //                 'enableMultiSort' => true,
    //                 'params' => $params
    //             ]
    //         ]);
    //     } catch (Throwable $e) {
    //         throw $e;
    //     }
    // }
}
