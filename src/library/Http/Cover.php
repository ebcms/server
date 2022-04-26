<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Http;

use App\Ebcms\Admin\Http\Common;
use Composer\InstalledVersions;
use DiggPHP\Session\Session;
use Exception;
use Throwable;
use ZipArchive;

class Cover extends Common
{
    public function get(
        Session $session
    ) {
        try {
            $upgrade = $session->get('upgrade');
            $root_path = InstalledVersions::getRootPackage()['install_path'];
            $this->unZip($upgrade['tmpfile'], $root_path);
            return $this->success('文件更新成功!');
        } catch (Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    private function unZip($file, $destination)
    {
        $zip = new ZipArchive();
        if ($zip->open($file) !== true) {
            throw new Exception('Could not open archive');
        }
        if (true !== $zip->extractTo($destination)) {
            throw new Exception('Could not extractTo ' . $destination);
        }
        if (true !== $zip->close()) {
            throw new Exception('Could not close archive ' . $file);
        }
    }
}
