<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'defaultRoute' => 'gallery/index',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'jgkjh8980',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true, //отменяет переход по url, если маршрут не прописан в rules
            'rules' => [
                'gallery-<status>' => 'gallery/index', //правило для ЧПУ (для страниц с общими галлереями)
                // принцип описан ниже
                /**
                 * сдесь индекс массива - шаблон URL, а значение путь - контроллер/действие
                 * если ссылка прописана как, Url::to(['/gallery/index', 'status' => 'friend']),
                 * то при срабатывании этой ссылки в адресной строке после http://www.gallery.dev/
                 * появится такой URL: gallery-friend, при этом ссылка отработает по параметру 'status' => 'friend'
                 * и откроется страница с галлереей друзей,
                 * если написать в адресной строке вручную написать некрасивый URL:
                 * http://www.gallery.dev/gallery/index?status=friend - результат будет такой же
                 * правило для ЧПУ можно описать в виде массива (это то же самое только представлено по другому
                 * и есть дополнительный параметр
                 */
                   //правила ЧПУ для отдельных станиц с конкретными галлереями
                [
                    'pattern' => 'gallery-<status>/№<galleryId>',
                    'route' => 'gallery/works',
                    'suffix' => '',
                ],
                //правила ЧПУ для конкретных работ
                [
                    'pattern' => 'work№<workId>(redirect-<redirect>)',
                    'route' => 'gallery/concrete-work'
                ]


            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
