<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Server\Traits\DirTrait;
use Composer\InstalledVersions;
use DiggPHP\Session\Session;
use Throwable;

class Rollback extends Common
{
    use DirTrait;

    public function get(
        Session $session
    ) {
        try {
            $upgrade = $session->get('upgrade');
            $root_path = InstalledVersions::getRootPackage()['install_path'];
            foreach ($upgrade['backup_dirs'] as $dir) {
                if (file_exists($root_path . $dir)) {
                    unlink($root_path . $dir);
                } elseif (is_dir($root_path . $dir)) {
                    $this->delDir($root_path . $dir);
                }
            }
            $this->copyDir($upgrade['backup_path'], $root_path);
        } catch (Throwable $th) {
            return $this->error('还原失败：' . $th->getMessage());
        }
    }
}
