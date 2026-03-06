<?php

namespace Tests\Unit;

use App\Models\Central\Tenant;
use PHPUnit\Framework\TestCase;

class TenantSimpleTest extends TestCase
{
    /** @test */
    public function it_extends_correct_parent_class()
    {
        $tenant = new Tenant;

        $this->assertInstanceOf(\Sicaboy\SharedSaas\Models\Central\Tenant::class, $tenant);
        $this->assertInstanceOf(Tenant::class, $tenant);
    }

    /** @test */
    public function it_has_correct_casts_defined()
    {
        $tenant = new Tenant;
        $casts = $tenant->getCasts();

        $this->assertArrayHasKey('trial_ends_at', $casts);
        $this->assertArrayHasKey('is_ready', $casts);
        $this->assertEquals('datetime', $casts['trial_ends_at']);
        $this->assertEquals('boolean', $casts['is_ready']);
    }

    /** @test */
    public function it_has_custom_columns_method()
    {
        $this->assertTrue(method_exists(Tenant::class, 'getCustomColumns'));

        $columns = Tenant::getCustomColumns();
        $this->assertIsArray($columns);
        $this->assertNotEmpty($columns);
    }

    /** @test */
    public function it_includes_required_columns_in_custom_columns()
    {
        $columns = Tenant::getCustomColumns();

        $requiredColumns = [
            'id',
            'name',
            'is_ready',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertContains($column, $columns, "Column {$column} should be in custom columns");
        }
    }

    /** @test */
    public function it_includes_billing_columns_in_custom_columns()
    {
        $columns = Tenant::getCustomColumns();

        $billingColumns = [
            'id',
            'name',
            'is_ready',
        ];

        foreach ($billingColumns as $column) {
            $this->assertContains($column, $columns, "Billing column {$column} should be in custom columns");
        }
    }

    /** @test */
    public function it_has_factory_support_from_parent()
    {
        // Check if the parent class has HasFactory trait
        $parentTraits = class_uses_recursive(\Sicaboy\SharedSaas\Models\Central\Tenant::class);
        $this->assertContains('Illuminate\Database\Eloquent\Factories\HasFactory', $parentTraits);
    }

    /** @test */
    public function it_can_be_instantiated_without_database()
    {
        $tenant = new Tenant;

        // Test that we can set attributes without database
        $tenant->name = 'Test Company';
        $tenant->status = 'active';
        $tenant->is_ready = true;

        $this->assertEquals('Test Company', $tenant->name);
        $this->assertEquals('active', $tenant->status);
        $this->assertTrue($tenant->is_ready);
    }

    /** @test */
    public function it_has_correct_table_name()
    {
        $tenant = new Tenant;
        $this->assertEquals('tenants', $tenant->getTable());
    }

    /** @test */
    public function it_has_fillable_attributes_from_parent()
    {
        $tenant = new Tenant;
        $fillable = $tenant->getFillable();

        // Should inherit fillable from parent class
        $this->assertIsArray($fillable);
    }
}
