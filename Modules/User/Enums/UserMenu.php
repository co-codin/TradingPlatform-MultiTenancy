<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use BenSampo\Enum\Enum;

final class UserMenu extends Enum
{
    public const MENU_CUSTOMERS = 'view_customers_menu';

    public const MENU_CUSTOMERS_CUSTOMERS_LIST = 'view_customers_customers_list_menu';

    public const MENU_CUSTOMERS_CONVERSION = 'view_customers_conversion_menu';

    public const MENU_CUSTOMERS_RETENTION = 'view_customers_retention_menu';

    public const MENU_CUSTOMERS_SPLITTER = 'view_customers_splitter_menu';

    public const MENU_CUSTOMERS_COMPLIANCE = 'view_customers_compliance_menu';

    public const MENU_CUSTOMERS_SUPPORT_TICKETS = 'view_customers_support_tickets_menu';

    public const MENU_CUSTOMERS_ACCOUNT_TYPES = 'view_customers_account_types_menu';

    public const MENU_FINANCE = 'view_finance_menu';

    public const MENU_FINANCE_TRANSACTIONS = 'view_finance_transactions_menu';

    public const MENU_FINANCE_EXCHANGES = 'view_finance_exchanges_menu';

    public const MENU_REPORTS = 'view_reports_menu';

    public const MENU_MARKETING = 'view_marketing_menu';

    public const MENU_MARKETING_AFFILIATION = 'view_marketing_affiliation_menu';

    public const MENU_EMAIL_TEMPLATES = 'view_marketing_email_templates_menu';

    public const MENU_WORKERS = 'view_workers_menu';

    public const MENU_WORKERS_WORKERS_LIST = 'view_workers_workers_list_menu';

    public const MENU_WORKERS_ROLES = 'view_workers_roles_menu';

    public const MENU_WORKERS_DESKS = 'view_workers_desks_menu';

    public const MENU_WORKERS_DEPARTMENTS = 'view_workers_departments_menu';

    public const MENU_ADMINISTRATION = 'view_administration_menu';

    public const MENU_ADMINISTRATION_VOIP = 'view_administration_voip_menu';

    public const MENU_ADMINISTRATION_NOTIFICATIONS = 'view_administration_notifications_menu';

    public const MENU_ADMINISTRATION_API_TOKENS = 'view_administration_api_tokens_menu';

    public const MENU_ADMINISTRATION_GLOBAL_CONFIG = 'view_administration_global_config_menu';

    public const MENU_ADMINISTRATION_RESTRICTED_COUNTRIES = 'view_administration_restricted_countries_menu';

    public const MENU_ADMINISTRATION_SUSPENDS = 'view_administration_suspends_menu';

    public static function menu(): array
    {
        return [
            ['permission' => self::MENU_CUSTOMERS, 'name' => 'Customers', 'link' => '', 'submenu' => self::customers()],
            ['permission' => self::MENU_FINANCE, 'name' => 'Finance', 'link' => '', 'submenu' => self::finance()],
            ['permission' => self::MENU_REPORTS, 'name' => 'Reports', 'link' => '', 'submenu' => []],
            ['permission' => self::MENU_MARKETING, 'name' => 'Marketing', 'link' => '', 'submenu' => self::marketing()],
            ['permission' => self::MENU_WORKERS, 'name' => 'Workers', 'link' => '', 'submenu' => self::workers()],
            ['permission' => self::MENU_ADMINISTRATION, 'name' => 'Administration', 'link' => '', 'submenu' => self::administration()],
        ];
    }

    public static function customers(): array
    {
        return [
            ['permission' => self::MENU_CUSTOMERS_CUSTOMERS_LIST, 'name' => 'Customers list', 'link' => ''],
            ['permission' => self::MENU_CUSTOMERS_CONVERSION, 'name' => 'Conversion', 'link' => ''],
            ['permission' => self::MENU_CUSTOMERS_RETENTION, 'name' => 'Retention', 'link' => ''],
            ['permission' => self::MENU_CUSTOMERS_SPLITTER, 'name' => 'Splitter', 'link' => ''],
            ['permission' => self::MENU_CUSTOMERS_COMPLIANCE, 'name' => 'Compliance', 'link' => ''],
            ['permission' => self::MENU_CUSTOMERS_SUPPORT_TICKETS, 'name' => 'Support Tickets', 'link' => ''],
            ['permission' => self::MENU_CUSTOMERS_ACCOUNT_TYPES, 'name' => 'Account Types', 'link' => ''],
        ];
    }

    public static function finance(): array
    {
        return [
            ['permission' => self::MENU_FINANCE_TRANSACTIONS, 'name' => 'Transactions', 'link' => ''],
            ['permission' => self::MENU_FINANCE_EXCHANGES, 'name' => 'Exchanges', 'link' => ''],
        ];
    }

    public static function marketing(): array
    {
        return [
            ['permission' => self::MENU_MARKETING_AFFILIATION, 'name' => 'Affiliation', 'link' => ''],
            ['permission' => self::MENU_EMAIL_TEMPLATES, 'name' => 'Email Templates', 'link' => ''],
        ];
    }

    public static function workers(): array
    {
        return [
            ['permission' => self::MENU_WORKERS_WORKERS_LIST, 'name' => 'Workers list', 'link' => ''],
            ['permission' => self::MENU_WORKERS_ROLES, 'name' => 'Roles', 'link' => ''],
            ['permission' => self::MENU_WORKERS_DESKS, 'name' => 'Desks', 'link' => ''],
            ['permission' => self::MENU_WORKERS_DEPARTMENTS, 'name' => 'Departments', 'link' => ''],
        ];
    }

    public static function administration(): array
    {
        return [
            ['permission' => self::MENU_ADMINISTRATION_VOIP, 'name' => 'VoIP', 'link' => ''],
            ['permission' => self::MENU_ADMINISTRATION_NOTIFICATIONS, 'name' => 'Notifications', 'link' => ''],
            ['permission' => self::MENU_ADMINISTRATION_API_TOKENS, 'name' => 'API Tokens', 'link' => ''],
            ['permission' => self::MENU_ADMINISTRATION_GLOBAL_CONFIG, 'name' => 'Global Config', 'link' => ''],
            ['permission' => self::MENU_ADMINISTRATION_RESTRICTED_COUNTRIES, 'name' => 'Restricted Countries', 'link' => ''],
            ['permission' => self::MENU_ADMINISTRATION_SUSPENDS, 'name' => 'Suspends', 'link' => ''],
        ];
    }
}
