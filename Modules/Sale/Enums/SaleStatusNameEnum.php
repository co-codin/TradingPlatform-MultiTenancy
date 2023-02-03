<?php

declare(strict_types=1);

namespace Modules\Sale\Enums;

use App\Enums\BaseEnum;

final class SaleStatusNameEnum extends BaseEnum
{
    public const REASSIGN = 'reassign';

    public const NO_ANSWER = 'no_answer';

    public const CALL_AGAIN = 'call_again';

    public const DUPLICATE_ACCOUNT = 'duplicate_account';

    public const WRONG_PERSON = 'wrong_person';

    public const UNDERAGE = 'underage';

    public const FIRST_ATTEMPT = 'first_attempt';

    public const TEST_LEAD = 'test_lead';

    public const NOT_INTERESTED = 'not_interested';

    public const CHECK_NUMBER = 'check_number';

    public const IN_THE_MONEY = 'in_the_money';

    public const NEW = 'new';

    public const NO_CALL = 'no_call';

    public const FIRST_ASSIGN = 'first_assign';

    public const TRY_FROM_OTHERS = 'try_from_others';

    /**
     * Conversion sale status list
     *
     * @return array
     */
    public static function conversionSaleStatusList(): array
    {
        return [
            self::REASSIGN,
            self::NO_ANSWER,
            self::CALL_AGAIN,
            self::DUPLICATE_ACCOUNT,
            self::WRONG_PERSON,
            self::UNDERAGE,
            self::FIRST_ATTEMPT,
            self::TEST_LEAD,
        ];
    }

    /**
     * Retention sale status list
     *
     * @return array
     */
    public static function retentionSaleStatusList(): array
    {
        return [
            self::NO_ANSWER,
            self::CALL_AGAIN,
            self::NOT_INTERESTED,
            self::CHECK_NUMBER,
            self::IN_THE_MONEY,
            self::NEW,
            self::NO_CALL,
            self::FIRST_ASSIGN,
            self::REASSIGN,
            self::TRY_FROM_OTHERS,
        ];
    }
}
