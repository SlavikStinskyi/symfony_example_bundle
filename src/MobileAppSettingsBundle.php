<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MobileAppSettingsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
