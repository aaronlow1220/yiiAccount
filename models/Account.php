<?php

namespace app\models;

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
 *   @OA\Property(property="type", type="string", description="accounting type", maxLength=128),
 *   @OA\Property(property="note", type="string", description="note", maxLength=128),
 *   @OA\Property(property="created_at", type="integer", description="unixtime", maxLength=10),
 *   @OA\Property(property="updated_at", type="integer", description="unixtime", maxLength=10),
 */
class Account extends ActiveRecord
{
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