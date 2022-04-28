<?php

use App\Ebcms\Server\Model\Server;
use DiggPHP\Router\Router;
use DiggPHP\Framework\Framework;

return Framework::execute(function (
    Server $server,
    Router $router
): array {
    $res = [];

    if ($data = $server->query('/check')) {
        if (!$data['code']) {
            $res[] = [
                'title' => '系统升级',
                'body' => '<a href="' . $router->build('/ebcms/server/index') . '">' . $data['message'] . '</a>',
                'tags' => ['remind'],
            ];
        }
    }
    return $res;
});
