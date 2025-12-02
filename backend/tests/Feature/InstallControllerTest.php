<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
use Mockery;

class InstallControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_reports_system_requirements(): void
    {
        File::shouldReceive('exists')->once()->andReturn(true);

        $response = $this->getJson('/api/install/status');

        $response->assertOk()
            ->assertJsonStructure([
                'php_version',
                'extensions',
                'storage_writable',
                'bootstrap_writable',
                'env_exists',
            ])
            ->assertJsonPath('env_exists', true);
    }

    public function test_create_env_builds_environment_file(): void
    {
        File::shouldReceive('exists')->once()->andReturn(false);
        File::shouldReceive('put')->once();

        $payload = [
            'app_name' => 'Portal QA',
            'app_url' => 'https://portal.test',
            'db_host' => 'localhost',
            'db_port' => 3306,
            'db_database' => 'portal',
            'db_username' => 'portal',
            'db_password' => 'secret',
        ];

        $this->postJson('/api/install/env', $payload)
            ->assertOk()
            ->assertJson(['status' => 'env_created']);
    }

    public function test_configure_database_updates_configuration_and_connectivity(): void
    {
        $connection = Mockery::mock();
        $connection->shouldReceive('getPdo')->once()->andReturnTrue();

        DB::shouldReceive('connection')->once()->with('mysql')->andReturn($connection);
        DB::shouldReceive('purge')->once()->with('mysql');
        DB::shouldReceive('reconnect')->once()->with('mysql');

        $payload = [
            'db_host' => 'db',
            'db_port' => 3306,
            'db_database' => 'portal_testing',
            'db_username' => 'tester',
            'db_password' => 'password',
        ];

        $this->postJson('/api/install/database', $payload)
            ->assertOk()
            ->assertJson(['status' => 'database_ready']);

        $this->assertSame('mysql', Config::get('database.default'));
        $this->assertSame('portal_testing', Config::get('database.connections.mysql.database'));
    }

    public function test_run_migrations_invokes_artisan_command(): void
    {
        Config::set('database.connections.mysql', ['driver' => 'mysql']);
        Artisan::shouldReceive('call')->once()->with('migrate', ['--force' => true]);

        $this->postJson('/api/install/migrate')
            ->assertOk()
            ->assertJson(['status' => 'migrations_finished']);
    }

    public function test_generate_key_returns_current_app_key(): void
    {
        Artisan::shouldReceive('call')->once()->with('key:generate', ['--force' => true]);
        Config::set('app.key', 'base64:test-key');

        $this->postJson('/api/install/key')
            ->assertOk()
            ->assertJson([
                'status' => 'key_generated',
                'app_key' => 'base64:test-key',
            ]);
    }

    public function test_create_admin_provisions_default_admin_user(): void
    {
        $payload = [
            'name' => 'Portal Admin',
            'email' => 'admin@portal.test',
            'password' => 'password123',
            'phone' => '+123456789',
        ];

        $this->postJson('/api/install/admin', $payload)
            ->assertOk()
            ->assertJson(['status' => 'admin_created']);

        $adminRole = Role::where('slug', 'admin')->first();
        $this->assertNotNull($adminRole);
        $this->assertTrue($adminRole->is_default);

        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->is_active);
        $this->assertEquals($adminRole->id, $user->role_id);
    }

    public function test_run_seeder_invokes_artisan_seed_command(): void
    {
        Artisan::shouldReceive('call')->once()->with('db:seed', ['--force' => true]);

        $this->postJson('/api/install/seed')
            ->assertOk()
            ->assertJson(['status' => 'seed_completed']);
    }
}
