<?php

namespace Tests;

use App\Models\Central\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    protected $tenancy = false;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    protected function tearDown(): void
    {
        // 显式清理测试期间产生的所有 tenants 以便 Stancl\Tenancy 触发 deleted 事件自动删除 sqlite 物理文件
        if (class_exists(Tenant::class)) {
            Tenant::all()->each(function ($tenant) {
                // By deleting the model instance, it triggers stancl/tenancy event to unlink db files
                $tenant->delete();
            });
        }

        parent::tearDown();
    }

    public function initializeTenancy()
    {
        /** @var Tenant $tenant */
        $tenant = Tenant::factory()->create();
        tenancy()->initialize($tenant);
    }

    /**
     * Create a tenant for testing
     */
    protected function createTenant(array $attributes = []): Tenant
    {
        return Tenant::factory()->create($attributes);
    }

    /**
     * Set up environment variables for testing
     */
    protected function setUpEnvironment($app)
    {
        // Use SQLite in-memory database for faster testing
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set testing-specific configurations
        $app['config']->set('app.env', 'testing');
        $app['config']->set('cache.default', 'array');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('mail.default', 'array');
        $app['config']->set('logging.default', 'null');
    }
}
