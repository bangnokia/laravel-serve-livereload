<?php

namespace BangNokia\ServeLiveReload\Tests;

use BangNokia\ServeLiveReload\Commands\ServeWebSocketsCommand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class InjectScriptsToResponseTest extends TestCase
{
    protected $router;

    public function setUp(): void
    {
        parent::setUp();

        Cache::put('serve_websockets_running', true, 10);
        $this->router = Route::middleware('web');
    }

    public function getPackageProviders($app)
    {
        return [
            \BangNokia\ServeLiveReload\ResponseServiceProvider::class,
        ];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        parent::resolveApplicationHttpKernel($app);
        $app->make(\Illuminate\Contracts\Http\Kernel::class);
    }

    public function test_it_inject_scripts_to_get_request()
    {
        $this->router->get('/_test/html', function () {
            return 'html';
        });

        $this->get('/_test/html')
            ->assertSee('ws://127.0.0.1:'.ServeWebSocketsCommand::port());
    }

    public function test_it_doesnt_inject_script_to_post_request()
    {
        $this->router->post('/_test/html', function () {
            return 'html';
        });

        $this->post('/_test/html')
            ->assertSee('html')
            ->assertDontSee('ws://127.0.0.1:'.ServeWebSocketsCommand::port());
    }

    public function test_it_doesnt_inject_scripts_to_json_response()
    {
        $this->router->get('/_test/json', function () {
            return ['name' => 'John Doe'];
        });
        $this->router->get('/_test/json2', function () {
            return response()->json(['name' => 'John Doe2']);
        });

        $this->get('/_test/json')
            ->assertJson(['name' => 'John Doe']);

        $this->get('/_test/json2')
            ->assertJson(['name' => 'John Doe2']);
    }

    public function test_it_doesnt_inject_script_to_ajax_response()
    {
        $this->router->get('/_test/html', function () {
            return 'html string';
        });

        $this->get('/_test/html', ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
            ->assertSee('html string')
            ->assertDontSee('ws://127.0.0.1:'.ServeWebSocketsCommand::port());
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
