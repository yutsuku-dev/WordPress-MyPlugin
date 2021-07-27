<?php

declare(strict_types=1);

namespace Yutsuku\WordPress;

class MyPlugin extends PluginBase
{
    public function __construct(string $pluginRootFile)
    {
        $this->pluginRootFile = $pluginRootFile;
    }
}
