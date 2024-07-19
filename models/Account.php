<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @OA\Schema(
 *   schema="Account",
 *   title="Account Model",
 *   description="This model is used to store accounting information.",
 *   @OA\Property(property="id", type="integer", description="ID #autoIncrement #pk", maxLength=20),
 *   @OA\Property(property="serial_number", type="string", description="Serial number of accounting", maxLength=16),
 *   @OA\Property(property="name", type="string", description="Chinese name of accounting item", maxLength=128),
 *   @OA\Property(property="en_name", type="string", description="English name of accounting item", maxLength=128),
 *   @OA\Property(property="parent_id", type="integer", description="ID of parent node", maxLength=10),
 *   @OA\Property(property="count", type="integer", description="Number of next one layer child node", maxLength=10),
 *   @OA\Property(property="level", type="integer", description="Current node level in tree structure", maxLength=10),
 *   @OA\Property(property="is_debit", type="string", enum={"0", "1"}, description="Debit or credit 0: credit 1: debit", maxLength=10),
 *   @OA\Property(property="type", type="string", description="accounting type", maxLength=128, default=""),
 *   @OA\Property(property="note", type="string", description="note", maxLength=128),
 *   @OA\Property(property="for_statement", type="string", enum={"0", "1"}, description="Whether statement is needed 0: no 1: yes", maxLength=10),
 *   @OA\Property(property="is_need_purchase_order", type="string", enum={"0", "1"}, description="Whether purchase order is needed 0: no 1: yes", maxLength=10),
 *   @OA\Property(property="created_at", type="integer", description="unixtime", maxLength=10),
 *   @OA\Property(property="updated_at", type="integer", description="unixtime", maxLength=10),
 * )
 */
class Account extends ActiveRecord
{
    /**
     * Use timestamp to store time of login, update and create.
     *
     * @return array<int, mixed>
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Define rules of account.
     *
     * @return array<int, mixed>
     */
    public function rules()
    {
        return [
            [['serial_number', 'name', 'en_name', 'parent_id', 'count', 'level', 'is_debit', 'type', 'note'], 'trim'],
            [['serial_number', 'name', 'en_name', 'type', 'note'], 'string'],
            [['parent_id', 'count', 'level'], 'integer'],
            [['is_debit', 'for_statement', 'is_need_to_purchase_order'], 'in', 'range' => ['0', '1']],
        ];
    }

    /**
     * Return table name of account_names.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'account_names';
    }
}
