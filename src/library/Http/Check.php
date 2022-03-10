<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Server\Model\Server;
use Throwable;

class Check extends Common
{
    public function get(
        Server $server
    ) {
        try {
            $res = $server->query('/check');
            if (!$res['status']) {
                return $this->error($res['message']);
            }
            return $this->success($res['message'], $res['data']);
        } catch (Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
