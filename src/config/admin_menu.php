<?php

use DiggPHP\Router\Router;
use DiggPHP\Framework\Framework;

return Framework::execute(function (
    Router $router
): array {
    $res = [];
    $res[] = [
        'title' => '系统升级',
        'url' => $router->build('/ebcms/server/index'),
    ];
    return $res;
});
