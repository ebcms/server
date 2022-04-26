<?php

use App\Ebcms\Server\Model\Server;
use DiggPHP\Router\Router;
use DiggPHP\Framework\Framework;

return [
    'menus' => Framework::execute(function (
        Server $server,
        Router $router
    ): array {
        $res = [];
        if ($data = $server->query('/check')) {
            if (!$data['code']) {
                $res[] = [
                    'title' => '系统升级',
                    'url' => $router->build('/ebcms/server/index'),
                    'tips' => $data['message'],
                    'tags' => ['menu'],
                    'priority' => 0,
                    'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg class="icon" style="width: 1em;height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="9251"><path d="M512 0c409.6 0 512 102.4 512 512S921.6 1024 512 1024 0 921.6 0 512 102.4 0 512 0z" fill="#49B7FB" p-id="9252"></path><path d="M525.645531 866.538057c-26.17344 52.79744-81.598171 55.769966-81.598171 55.769966s37.139017-34.739931 34.628754-81.7152C358.786194 815.221029 267.702857 690.310583 267.702857 539.911314c0-168.690834 114.629486-305.485531 256-305.485531s256 136.794697 256 305.485531c0 159.310994-102.165943 290.055314-232.57088 304.227475-15.886629 6.395611-17.372891 11.199634-21.486446 22.399268z" fill="#33ACF7" p-id="9253"></path><path d="M513.942674 831.429486c-26.17344 52.79744-81.598171 55.769966-81.598171 55.769965s37.139017-34.739931 34.628754-81.7152C347.083337 780.112457 256 655.202011 256 504.802743c0-168.690834 114.629486-305.485531 256-305.485532s256 136.794697 256 305.485532c0 159.310994-102.165943 290.055314-232.57088 304.227474-15.886629 6.395611-17.372891 11.199634-21.486446 22.399269z" fill="#FFFFFF" p-id="9254"></path><path d="M404.918857 500.455131a12560.01536 12560.01536 0 0 0 109.714286-111.428754 12559.986103 12559.986103 0 0 0 109.714286 111.428754c-25.717029-0.117029-51.434057-0.117029-77.034058-0.117028v137.947428H482.05824V500.226926c-25.828206 0.117029-51.545234 0.117029-77.145234 0.234057z" fill="#3CA2EC" p-id="9255"></path></svg>'),
                ];
            }
        }
        return $res;
    }),
];
