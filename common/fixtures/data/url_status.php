<?php

use backend\components\UrlChecker;
use yii\db\Expression;

$status_codes = [
    200, 201, 202,
    300, 301, 302, 303, 304, 305,
    400, 401, 402, 403, 404, 405,
    500, 501, 502, 503,
];

$urls = array_map(function ($status_code) {
    return 'https://httpstat.us/' . $status_code;
}, $status_codes);

$urlChecker = new UrlChecker();

$responses = array_map([$urlChecker, 'getStatusCodeFromUrl'], $urls);

$fixtures = [];
for ($i = 0; $i < count($urls); $i++) {
    $fixtures[$status_codes[$i]] = [
        'url' => $urls[$i],
        'hash_string' => md5($urls[$i]),
        'status_code' => $responses[$i],
        'query_count' => 1,
        'created_at' => new Expression('NOW() - INTERVAL ROUND(RAND() * 14 - 4) DAY'),
        'updated_at' => new Expression('NOW() - INTERVAL ROUND(RAND() * 3) DAY'),
    ];
}

return $fixtures;
