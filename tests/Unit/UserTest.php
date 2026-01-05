<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_user_is_admin_method()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pegawai = User::factory()->create(['role' => 'pegawai']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($pegawai->isAdmin());
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create(['password' => 'plaintext']);

        $this->assertNotEquals('plaintext', $user->password);
        $this->assertTrue(password_verify('plaintext', $user->password));
    }
}

