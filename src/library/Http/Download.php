<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Http;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Session\Session;
use Throwable;

class Download extends Common
{

    public function get(
        Session $session
    ) {
        try {
            $upgrade = $session->get('upgrade');
            if (false === $content = file_get_contents($upgrade['source'], false, stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]))) {
                return $this->error('升级包下载失败，请稍后再试~');
            }

            if (md5($content) != $upgrade['md5']) {
                return $this->error('校验失败！');
            }

            $tmpfile = tempnam(sys_get_temp_dir(), 'serverupgrade');

            if (false === file_put_contents($tmpfile, $content)) {
                return $this->error('文件写入失败，请检查权限~');
            }
            $upgrade['tmpfile'] = $tmpfile;
            $session->set('upgrade', $upgrade);

            return $this->success('下载成功！');
        } catch (Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
