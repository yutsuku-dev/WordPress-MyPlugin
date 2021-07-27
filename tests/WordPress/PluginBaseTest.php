<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Yutsuku\WordPress\PluginBase;

/**
 * @coversDefaultClass Yutsuku\WordPress\PluginBase
 */
final class PluginBaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Monkey\tearDown();
    }

    /**
     * @covers ::addActions
     */
    public function testAddActions(): void
    {
        Actions\expectAdded('init');
        Actions\expectAdded('rest_api_init');

        $stub = $this->getMockForAbstractClass(PluginBase::class, [__FILE__]);
        $stub->addActions();
    }

    /**
     * @covers ::addFilters
     */
    public function testAddFilters(): void
    {
        Filters\expectAdded('query_vars');
        Filters\expectAdded('script_loader_tag');
        Filters\expectAdded('template_include');

        $stub = $this->getMockForAbstractClass(PluginBase::class, [__FILE__]);
        $stub->addFilters();
    }

    /**
     * @covers ::enqueueResources
     */
    public function testEnqueueResources(): void
    {
        Actions\expectAdded('wp_enqueue_scripts');

        $stub = $this->getMockForAbstractClass(PluginBase::class, [__FILE__]);
        $stub->enqueueResources();
    }

    /**
     * @covers ::enable
     */
    public function testEnable(): void
    {
        Functions\expect('register_activation_hook');
    
        $stub = $this->getMockForAbstractClass(PluginBase::class, [__FILE__]);
        $stub->enable();
    }

    /**
     * @covers ::populateMetadata
     * @covers ::metadataHandle
     */
    public function testPopulateMetadata(): void
    {
        Functions\when('get_file_data')->justReturn(['Plugin Name' => __METHOD__]);
    
        $stub = $this->getMockForAbstractClass(PluginBase::class, [__FILE__]);
        $stub->populateMetadata();

        $this->assertStringContainsString(__METHOD__, $stub->metadataHandle());
    }
}