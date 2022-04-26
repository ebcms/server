<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Server\Model\Server;
use DiggPHP\Psr16\LocalAdapter;
use DiggPHP\Router\Router;
use DiggPHP\Session\Session;
use Throwable;

class Source extends Common
{
    public function get(
        Server $server,
        Router $router,
        LocalAdapter $cache,
        Session $session
    ) {
        try {
            $token = md5(uniqid());
            $cache->set('serverapitoken', $token, 30);
            $res = $server->query('/source', [
                'api' => $router->build('/ebcms/server/api', [
                    'token' => $token
                ]),
            ]);
            if ($res['code']) {
                return $this->error($res['message'], $res['redirect_url'] ?? '', $res['code']);
            }
            if (null === $upgrade = $cache->get('serversource')) {
                return $this->error('超时，请重新操作~');
            }
            $session->set('upgrade', $upgrade);
            return $this->success($res['message']);
        } catch (Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
