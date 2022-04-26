<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Http;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Template\Template;

class Index extends Common
{
    public function get(
        Template $template
    ) {
        return $template->renderFromFile('index@ebcms/server');
    }
}
