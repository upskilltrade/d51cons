<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_with_a_hashed_password(): void
    {
        $this->artisan('admin:create', ['--name' => 'Owner', '--email' => 'Owner@D51cons.test'])
            ->expectsQuestion('Password (at least 12 characters)', 'correct-horse-battery')
            ->assertSuccessful();

        $user = User::sole();
        $this->assertSame('owner@d51cons.test', $user->email);
        $this->assertTrue(Hash::check('correct-horse-battery', $user->password));
    }

    public function test_it_rejects_short_passwords(): void
    {
        $this->artisan('admin:create', ['--name' => 'Owner', '--email' => 'owner@d51cons.test'])
            ->expectsQuestion('Password (at least 12 characters)', 'short')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }
}
