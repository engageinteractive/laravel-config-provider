<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;

use Tests\TestCase;
use Tests\Fixtures\ConfigProvider;

class ConfigProviderTest extends TestCase
{
    public function test_get_NoParametersReturnsAll()
    {
        // When
        $actual = (new ConfigProvider)->get();

        // Then
        $this->assertEquals(config('config'), $actual);
    }

    public function test_get_KeyAccessValueAtPath()
    {
        // Given
        $this->assertNotNull(config('config.example'));

        // When
        $actual = (new ConfigProvider)->get('example');

        // Then
        $this->assertEquals(config('config.example'), $actual);
    }

    public function test_set_SetsAccessValueAtKeyPath()
    {
        // Given
        $expected = 'test';
        $this->assertNotEquals($expected, config('config.example'));

        // When
        (new ConfigProvider)->set('example', $expected);

        // Then
        $this->assertEquals(config('config.example'), $expected);
    }

    public function test_Given_CustomConfigKey_When_Called_Then_UsesCustomKey()
    {
        // Given
        $provider = new class extends ConfigProvider {
            protected $configKey = 'test';
        };

        Config::set('test', 'expected');
        Config::set('config', null);

        // When
        $actual = $provider->get();

        // Then
        $this->assertEquals(config('test'), $actual);
    }
}
