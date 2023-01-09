<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Modules\Department\Repositories\Criteria\DepartmentRequestCriteria;
use Modules\Desk\Repositories\Criteria\DeskRequestCriteria;
use Modules\Geo\Repositories\Criteria\CountryRequestCriteria;
use Modules\Language\Repositories\Criteria\LanguageRequestCriteria;
use Modules\Sale\Repositories\Criteria\SaleStatusRequestCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CustomerRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'user_id',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                self::allowedModelFields(),
                UserRequestCriteria::allowedModelFields('affiliateUser'),
                DeskRequestCriteria::allowedModelFields('desk'),
                DepartmentRequestCriteria::allowedModelFields('department'),
                CountryRequestCriteria::allowedModelFields('country'),
                LanguageRequestCriteria::allowedModelFields('language'),
                SaleStatusRequestCriteria::allowedModelFields('saleStatus'),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::partial('gender'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('phone'),
                AllowedFilter::partial('phone2'),
                AllowedFilter::partial('birthday'),
                AllowedFilter::partial('state'),
                AllowedFilter::partial('city'),
                AllowedFilter::partial('address'),
                AllowedFilter::partial('postal_code'),
                AllowedFilter::partial('verification_status_id'),
                AllowedFilter::partial('is_demo'),
                AllowedFilter::partial('is_active'),
                AllowedFilter::partial('is_active_trading'),
                AllowedFilter::partial('is_test'),
                AllowedFilter::partial('last_online'),
                AllowedFilter::partial('first_autologin_time'),
                AllowedFilter::partial('first_login_time'),
                AllowedFilter::partial('first_deposit_date'),
                AllowedFilter::partial('last_approved_deposit_date'),
                AllowedFilter::partial('last_pending_deposit_date'),
                AllowedFilter::partial('last_pending_withdrawal_date'),
                AllowedFilter::partial('last_communication_date'),
                AllowedFilter::partial('offer_name'),
                AllowedFilter::partial('offer_url'),
                AllowedFilter::partial('comment_about_customer'),
                AllowedFilter::partial('source'),
                AllowedFilter::partial('banned_at'),
                AllowedFilter::partial('click_id'),
                AllowedFilter::partial('free_param_1'),
                AllowedFilter::partial('free_param_2'),
                AllowedFilter::partial('free_param_3'),
                AllowedFilter::partial('balance'),
                AllowedFilter::partial('balance_usd'),
                AllowedFilter::partial('is_ftd'),
                AllowedFilter::partial('timezone'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::partial('updated_at'),

                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('language_id'),
                AllowedFilter::exact('supposed_language_id'),
                AllowedFilter::exact('platform_language_id'),
                AllowedFilter::exact('affiliate_user_id'),
                AllowedFilter::exact('conversion_user_id'),
                AllowedFilter::exact('retention_user_id'),
                AllowedFilter::exact('compliance_user_id'),
                AllowedFilter::exact('support_user_id'),
                AllowedFilter::exact('conversion_manager_user_id'),
                AllowedFilter::exact('retention_manager_user_id'),
                AllowedFilter::exact('first_conversion_user_id'),
                AllowedFilter::exact('first_retention_user_id'),
                AllowedFilter::exact('desk_id'),
                AllowedFilter::exact('department_id'),
                AllowedFilter::exact('campaign_id'),
                AllowedFilter::exact('conversion_sale_status_id'),
                AllowedFilter::exact('retention_sale_status_id'),

                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'country',
                'language',
                'supposedLanguage',
                'platformLanguage',
                'desk',
                'department',
                'campaign',

                'affiliateUser',
                'conversionUser',
                'retentionUser',
                'complianceUser',
                'supportUser',
                'conversionManageUser',
                'retentionManageUser',
                'firstConversionUser',
                'firstRetentionUser',
                'conversionSaleStatus',
                'retentionSaleStatus',
            ])
            ->allowedSorts([
                'id',
                'first_name',
                'last_name',
                'gender',
                'email',
                'password',
                'phone',
                'phone2',
                'birthday',
                'state',
                'city',
                'address',
                'postal_code',
                'is_demo',
                'is_active',
                'is_active_trading',
                'is_test',
                'last_online',
                'first_autologin_time',
                'first_login_time',
                'first_deposit_date',
                'last_approved_deposit_date',
                'last_pending_deposit_date',
                'last_pending_withdrawal_date',
                'last_communication_date',
                'banned_at',
                'offer_name',
                'offer_url',
                'comment_about_customer',
                'source',
                'click_id',
                'free_param_1',
                'free_param_2',
                'free_param_3',
                'balance',
                'balance_usd',
                'is_ftd',
                'timezone',
                'created_at',
                'updated_at',
                'deleted_at',
                'country_id',
                'language_id',
                'supposed_language_id',
                'platform_language_id',
                'verification_status_id',
                'affiliate_user_id',
                'conversion_user_id',
                'retention_user_id',
                'compliance_user_id',
                'support_user_id',
                'conversion_manager_user_id',
                'retention_manager_user_id',
                'first_conversion_user_id',
                'first_retention_user_id',
                'desk_id',
                'department_id',
                'campaign_id',
                'conversion_sale_status_id',
                'retention_sale_status_id',
            ]);
    }
}
