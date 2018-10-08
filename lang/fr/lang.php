<?php return [
    'plugin' => [
        'name' => 'KrakenOptimizer',
        'description' => 'Optimiser et compresser les images via kraken.io API'
    ],
    'permissions' => [
        'tab'             => 'Kraken Optimizer',
        'access_settings' => 'Accès à la configuration',
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
    ],
    'widgets' => [
        'kraken' => [
            'label' => 'Kraken Status',
            'title' => 'Kraken Status',
        ],
        'labels' => [
            'available' => 'Disponible',
            'used' => 'Utilisé',
        ]
    ],
];