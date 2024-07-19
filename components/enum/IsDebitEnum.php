<?php

namespace app\components\enum;

use MyCLabs\Enum\Enum;

/**
 * Debit Enum for is_debit.
 *
 * @extends  Enum<Action::*>
 */
final class IsDebitEnum extends Enum
{
    public const DEBIT = '1';
    public const CREDIT = '0';
}
