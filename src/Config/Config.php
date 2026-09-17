<?php

declare(strict_types=1);

namespace Src\Config;

class Config
{
    public function config(string $config)
    {
        [$cfg, $key] = explode('.', $config);
        $configs = require __DIR__ . "/../app/Configs/$cfg.php";
        return $configs[$key];
    }
}