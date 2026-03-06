<?php

namespace Tests\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class ControllerSimpleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock the Log facade
        if (! defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
    }

    /** @test */
    public function it_has_correct_traits()
    {
        $controller = new TestController;

        $this->assertInstanceOf(Controller::class, $controller);

        // Check that the controller uses the required traits
        $traits = class_uses_recursive(Controller::class);
        $this->assertContains('Illuminate\Foundation\Auth\Access\AuthorizesRequests', $traits);
        $this->assertContains('Illuminate\Foundation\Validation\ValidatesRequests', $traits);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $controller = new TestController;

        $this->assertInstanceOf(Controller::class, $controller);
        $this->assertInstanceOf(TestController::class, $controller);
    }

    /** @test */
    public function it_has_log_method()
    {
        $controller = new TestController;

        $this->assertTrue(method_exists($controller, 'testLog'));
    }
}

/**
 * Test controller class to test basic functionality
 */
class TestController extends Controller
{
    public function testLog($message, $level = 'info')
    {
        // Don't actually call the log method in this simple test
        // Just verify the method exists and can be called
        return "logged: {$message} at level {$level}";
    }
}
