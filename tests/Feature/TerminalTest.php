<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TerminalTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        return User::factory()->create(['role' => 'super_admin']);
    }

    public function test_halaman_terminal_hanya_bisa_diakses_super_admin(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('admin.terminal.index'))
            ->assertOk()
            ->assertSeeText('Terminal Artisan');

        $opd = User::factory()->create(['role' => 'opd']);

        $this->actingAs($opd)->get(route('admin.terminal.index'))->assertForbidden();
        $this->actingAs($opd)->post(route('admin.terminal.run'), ['command' => 'about'])->assertForbidden();
    }

    public function test_perintah_artisan_bisa_dijalankan(): void
    {
        $res = $this->actingAs($this->superAdmin())
            ->postJson(route('admin.terminal.run'), ['command' => 'php artisan about']);

        $res->assertOk()->assertJsonPath('ok', true);
        $this->assertStringContainsStringIgnoringCase('Laravel', $res->json('output'));
    }

    public function test_perintah_berbahaya_diblokir(): void
    {
        foreach (['migrate:fresh', 'db:wipe --force', 'tinker'] as $cmd) {
            $res = $this->actingAs($this->superAdmin())
                ->postJson(route('admin.terminal.run'), ['command' => $cmd]);

            $res->assertOk()->assertJsonPath('ok', false);
            $this->assertStringContainsString('DITOLAK', $res->json('output'));
        }
    }

    public function test_multi_perintah_dan_perintah_tak_dikenal_ditolak(): void
    {
        $res = $this->actingAs($this->superAdmin())
            ->postJson(route('admin.terminal.run'), ['command' => 'about; rm -rf /']);

        $res->assertOk()->assertJsonPath('ok', false);

        $res = $this->actingAs($this->superAdmin())
            ->postJson(route('admin.terminal.run'), ['command' => 'perintah-ngawur']);

        $res->assertOk()->assertJsonPath('ok', false);
        $this->assertStringContainsString('tidak dikenal', $res->json('output'));
    }

    public function test_sync_kecamatan_bisa_dijalankan_dari_terminal(): void
    {
        $res = $this->actingAs($this->superAdmin())
            ->postJson(route('admin.terminal.run'), ['command' => 'kecamatan:sync --without-desa --no-interaction']);

        $res->assertOk();
        $this->assertDatabaseCount('kecamatans', 9);
        $this->assertDatabaseHas('kecamatans', ['nama' => 'Loloda Tengah']);
        $this->assertDatabaseHas('kecamatans', ['nama' => 'Sahu Timur']);
        $this->assertDatabaseHas('kecamatans', ['nama' => 'Ibu Selatan']);
    }
}
