<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Http;

use App\Ebcms\Admin\Http\Common;
use Composer\InstalledVersions;
use DigPHP\Session\Session;
use function Composer\Autoload\includeFile;
use Throwable;

class Install extends Common
{
    public function get(
        Session $session
    ) {
        try {
            $upgrade = $session->get('upgrade');
            $root_path = InstalledVersions::getRootPackage()['install_path'];
            $upgrade_file = $root_path . '/upgrade.php';
            if (file_exists($upgrade_file)) {
                includeFile($upgrade_file);
            }

            if (file_exists($upgrade_file)) {
                unlink($upgrade_file);
            }
            if (file_exists($upgrade['tmpfile'])) {
                unlink($upgrade['tmpfile']);
            }

            $session->delete('upgrade');

            return $this->success('升级成功!');
        } catch (Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
