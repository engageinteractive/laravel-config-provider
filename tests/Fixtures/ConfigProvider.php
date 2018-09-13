<?php

namespace Tests\Fixtures;

use EngageInteractive\LaravelConfigProvider\ConfigProvider as BaseConfigProvider;

class ConfigProvider extends BaseConfigProvider
{
    /**
     * Key to use when retrieving config values. Override this in your
     * subclass if you need to use a different filename.
     *
     * @var string
     */
    protected $configKey = 'config';
}
