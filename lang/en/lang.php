<?php return [
    'plugin' => [
        'name' => 'KrakenOptimizer',
        'description' => 'Optimize and Compress images via kraken.io API'
    ],
    'permissions' => [
        'tab'             => 'Kraken Optimizer',
        'access_settings' => 'Access plugin configuration',
    ],
    'settings' => [
        'tabs' => [
            'kraken' => 'Kraken Configuration',
        ],
        'kraken_site_key' => 'Kraken Site Key', 
        'kraken_secret_key' => 'Kraken Secret Key',
    ],
    'errors' => [
        'optimization' => [
            'alert_errors' => 'Optimization error from kraken',
        ]
    ]
];