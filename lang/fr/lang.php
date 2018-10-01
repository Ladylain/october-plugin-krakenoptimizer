<?php return [
    'plugin' => [
        'name' => 'KrakenOptimizer',
        'description' => ''
    ],
    'permissions' => [
        'tab'             => 'Kraken Optimizer',
        'access_settings' => 'Access module configuration',
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
            'alert_errors' => 'Erreur lors de l\'optimization via kraken',
        ]
    ]
];