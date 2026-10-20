<?php

namespace App\Http\Controllers\Admin;

use App\AuditLogService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Exception\CommandNotFoundException;

/**
 * Terminal Artisan berbasis web — pengganti terminal cPanel.
 *
 * Keamanan:
 * - Hanya Super Admin (middleware route).
 * - Perintah dijalankan lewat Artisan::call() (tanpa shell exec),
 *   sehingga aman & tetap berfungsi walau fungsi exec/proc_open
 *   dinonaktifkan di shared hosting.
 * - Daftar hitam perintah berbahaya / interaktif / long-running.
 * - Hanya satu perintah per eksekusi (tanpa ; && || | dll).
 * - Setiap eksekusi dicatat ke Audit Log.
 */
class TerminalController extends Controller
{
    /**
     * Perintah yang dilarang dijalankan dari web terminal
     * (berisiko kehilangan data / interaktif / blocking).
     */
    protected array $blocked = [
        'db:wipe',
        'migrate:fresh',
        'migrate:refresh',
        'migrate:reset',
        'migrate:rollback',
        'down',
        'serve',
        'tinker',
        'test',
        'tail',
        'pail',
        'queue:work',
        'queue:listen',
        'schedule:work',
        'schedule:interrupt',
        'reverb:start',
        'vite',
    ];

    public function index()
    {
        // Artisan::all() kadang membuka output buffer (console components)
        // yang tidak ditutup — kembalikan level buffer agar respons bersih.
        $level = ob_get_level();

        $commands = collect(Artisan::all())
            ->keys()
            ->sort()
            ->values()
            ->all();

        while (ob_get_level() > $level) {
            ob_end_clean();
        }

        return view('admin.terminal.index', compact('commands'));
    }

    public function run(Request $request): JsonResponse
    {
        $request->validate(['command' => 'required|string|max:500']);

        $input = trim($request->input('command'));

        // Normalisasi: boleh ditulis "php artisan xxx" atau "artisan xxx"
        if (preg_match('/^(php\s+)?artisan\s+(.+)$/i', $input, $m)) {
            $input = trim($m[2]);
        }

        if ($input === '' || $input === 'clear') {
            return response()->json(['ok' => true, 'output' => '', 'cleared' => true]);
        }

        // Tolak percobaan multi-perintah / shell injection
        if (preg_match('/[;|&`$>]|(\bsource\b)|(\bdot\b)/', $input)) {
            return response()->json([
                'ok'  => false,
                'output' => "DITOLAK: hanya satu perintah artisan per eksekusi (karakter ; | & ` $ > tidak diizinkan).",
            ]);
        }

        // Nama perintah = token pertama (abaikan flag di depan)
        $parts  = preg_split('/\s+/', $input);
        $name   = ltrim($parts[0], '-');

        if (in_array($name, $this->blocked, true)) {
            return response()->json([
                'ok'  => false,
                'output' => "DITOLAK: perintah \"{$name}\" diblokir demi keamanan (berisiko merusak data / interaktif / blocking).",
            ]);
        }

        $started = microtime(true);

        try {
            if (function_exists('set_time_limit')) {
                @set_time_limit(300);
            }

            $exitCode = Artisan::call($input);
            $output   = Artisan::output();
            $ok       = $exitCode === 0;

            if ($output === '') {
                $output = $ok ? '(tidak ada output)' : "(exit code {$exitCode})";
            }
        } catch (CommandNotFoundException $e) {
            $ok     = false;
            $output = 'Perintah tidak dikenal: ' . $name . "\nKetik nama perintah yang valid, contoh: migrate --force";
        } catch (\Throwable $e) {
            $ok     = false;
            $output = 'Error: ' . $e->getMessage();
        }

        $durasi = round((microtime(true) - $started) * 1000);

        AuditLogService::log(
            'run',
            'Terminal',
            null,
            null,
            null,
            ['command' => $input, 'exit_code' => $exitCode ?? 1],
            "Web terminal: php artisan {$input}",
        );

        return response()->json([
            'ok'     => $ok,
            'output' => $output,
            'ms'     => $durasi,
        ]);
    }
}
