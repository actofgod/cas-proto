<?php
declare(strict_types=1);

return [
    'services' => [
        \App\Service\UserService::class => [
            'users' => [
                [
                    'username' => 'test',
                    'password' => password_hash('test', PASSWORD_BCRYPT),
                ],[
                    'username' => 'demo',
                    'password' => password_hash('demo', PASSWORD_BCRYPT),
                ],
            ],
        ],
        \App\Service\RewardService::class => [
            'rewards' => [
                [
                    'type' => 'item',
                    'weight' => 10,
                    'reward' => [
                        'id' => 1,
                        'item_id' => 3,
                    ],
                ],[
                    'type' => 'item',
                    'weight' => 20,
                    'reward' => [
                        'id' => 2,
                        'item_id' => 2,
                    ],
                ],[
                    'type' => 'item',
                    'weight' => 30,
                    'reward' => [
                        'id' => 3,
                        'item_id' => 1,
                    ],
                ],[
                    'type' => 'money',
                    'weight' => 30,
                    'reward' => [
                        'id' => 4,
                        'amount' => [
                            'min' => 1,
                            'max' => 10,
                        ],
                    ],
                ],[
                    'type' => 'money',
                    'weight' => 20,
                    'reward' => [
                        'id' => 5,
                        'amount' => [
                            'min' => 10,
                            'max' => 50,
                        ],
                    ],
                ],[
                    'type' => 'money',
                    'weight' => 10,
                    'reward' => [
                        'id' => 6,
                        'amount' => [
                            'min' => 90,
                            'max' => 100,
                        ],
                    ],
                ],[
                    'type' => 'points',
                    'weight' => 40,
                    'reward' => [
                        'id' => 7,
                        'amount' => [
                            'min' => 900,
                            'max' => 1000,
                        ],
                    ],
                ],[
                    'type' => 'points',
                    'weight' => 50,
                    'reward' => [
                        'id' => 8,
                        'amount' => [
                            'min' => 500,
                            'max' => 700,
                        ],
                    ],
                ],[
                    'type' => 'points',
                    'weight' => 1,
                    'reward' => [
                        'id' => 9,
                        'amount' => [
                            'min' => 100,
                            'max' => 200,
                        ],
                    ],
                ],
            ],
        ],
        \App\Service\ItemService::class => [
            'items' => [
                [
                    'id' => 1,
                    'name' => 'Item#1',
                    'quantity' => 3,
                ],[
                    'id' => 2,
                    'name' => 'Item#2',
                    'quantity' => 2,
                ],[
                    'id' => 3,
                    'name' => 'Item#3',
                    'quantity' => 1,
                ],
            ],
        ],
    ],
];