<?php

return [

    'deployment' => [
        'key' => env('JW145_DEPLOYMENT_SECURITY_KEY', null ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default admin user
    |--------------------------------------------------------------------------
    |
    | Default user will be created at project installation/deployment
    |
    */
    'admin_name' => env('JW145_ADMIN_NAME', ''),
    'admin_email' => env('JW145_ADMIN_EMAIL', ''),
    'admin_password' => env('JW145_ADMIN_PASSWORD', ''),
    'user_password' => env('JW145_USER_PASSWORD', ''),

    'company' => [
        'name' => env('JW145_COMPANY_NAME', ''),
        'address' => env('JW145_COMPANY_ADDRESS', ''),
        'phone' => env('JW145_COMPANY_PHONE', ''),
        'vat' => env('JW145_COMPANY_VAT', ''),
        'number' => env('JW145_COMPANY_NUMBER', ''),
    ],

    'pdf' => [
        'per_page_panels' => (int)  env('JW145_PDF_PANELS_PER_PAGE', 10),
    ],

    'per_page' => (int) env('JW145_PAGINATION_PERPAGE', '10'),
    'per_page_max' => (int) env('JW145_PAGINATION_PERPAGE_MAX', '100'),

    'user' => [
        'code_expires' => (int) env('JW145_USER_CODE_EXPIRES', 5),
    ]
];
