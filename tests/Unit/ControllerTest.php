<?php

namespace Tests\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    /** @test */
    public function it_logs_messages_with_default_info_level()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('testing - CENTRAL'."\n".'Test message');

        $controller = new TestControllerForLogging;
        $controller->testLog('Test message');
    }

    /** @test */
    public function it_logs_messages_with_specified_level()
    {
        Log::shouldReceive('error')
            ->once()
            ->with('testing - CENTRAL'."\n".'Error message');

        $controller = new TestControllerForLogging;
        $controller->testLog('Error message', 'error');
    }

    /** @test */
    public function it_logs_messages_with_warning_level()
    {
        Log::shouldReceive('warning')
            ->once()
            ->with('testing - CENTRAL'."\n".'Warning message');

        $controller = new TestControllerForLogging;
        $controller->testLog('Warning message', 'warning');
    }

    /** @test */
    public function it_logs_messages_with_critical_level()
    {
        Log::shouldReceive('critical')
            ->once()
            ->with('testing - CENTRAL'."\n".'Critical message');

        $controller = new TestControllerForLogging;
        $controller->testLog('Critical message', 'critical');
    }

    /** @test */
    public function it_includes_environment_in_log_message()
    {
        config(['app.env' => 'production']);

        Log::shouldReceive('info')
            ->once()
            ->with('production - CENTRAL'."\n".'Production message');

        $controller = new TestControllerForLogging;
        $controller->testLog('Production message');
    }
}

/**
 * Test controller class to expose protected log method
 */
class TestControllerForLogging extends Controller
{
    public function testLog($message, $level = 'info')
    {
        $this->log($message, $level);
    }
}
