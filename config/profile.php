<?php

use Lightuna\Middleware\Blocker\Blocker;
use Lightuna\Middleware\Blocker\Rule\CountryRule;
use Lightuna\Middleware\Blocker\Rule\SessionAuthRule;
use Lightuna\Middleware\Operator\Location;

return [
    'site' => [
        'domain' => 'localhost',
        'baseUrl' => '/lightuna',
        'defaultBoard' => 'develop',
        'environment' => 'dev',
        'imageUploadPrefix' => '/upload',
        'imageUploadPath' => __DIR__ . '/../upload',
        'allowFileType' => ['image/png', 'image/jpg', 'image/jpeg'],
        'logFilePath' => __DIR__ . '/../logs/info.log',
        'managerEmail' => 'admin@example.com',
        'masterPassword' => '4c94485e0c21ae6c41ce1dfe7b6bfaceea5ab68e40a2476f50208e526f506080',
        'gtags' => 'UA-66865036-1',
    ],
    'middleware' => [
        new Location(
            false,
            [
                '/lightuna/index.php',
                '/lightuna/trace.php',
                '/lightuna/post.php',
                '/lightuna/console.php'
            ],
            new Blocker(
                false,
                new CountryRule(true),
                new SessionAuthRule(true)
            )
        )
    ],
    'database' => [
        'type' => 'mariadb',
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'lightuna',
        'password' => 'lightuna',
        'schema' => 'lightuna',
        'options' => [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ]
    ],
    'boards' => [
        '__default__' => [
            'userName' => '이름 없음',
            'maxThreadView' => 5,
            'maxThreadListView' => 15,
            'maxResponseView' => 30,
            'maxResponseLineView' => 15,
            'maxTitleLength' => 50,
            'maxNameLength' => 60,
            'maxContentLength' => 20000,
            'maxResponseSize' => 1000,
            'maxResponseInterval' => 3,
            'maxDuplicateResponseInterval' => 10,
            'maxImageSize' => 1 * 1024 * 1024,
            'maxImageNameLength' => 80,
            'style' => 'default.css',
            'customWeek' => ['일', '월', '화', '수', '목', '금', '토'],
            'responseCountCriteria' => '10',
            'managerEmail' => 'admin@example.com'
        ],
        'develop' => [
            'uid' => 'develop',
            'name' => '테스트용',
            'userName' => '익명의 테스터',
            'maxResponseSize' => 300,
            'maxResponseInterval' => 1,
            'maxDuplicateResponseInterval' => 1,
            'style' => 'test.css',
        ]
    ],
    'blocker' => [
        'allowCountry' => [
            'KR'
        ],
        'sessionAuth' => [
            'key' => 'any-string-here'
        ]
    ]
];
