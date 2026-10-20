@extends('layouts.admin')
@section('page-title', 'Terminal Artisan')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-terminal me-2"></i> Terminal Artisan (pengganti terminal cPanel)</span>
                <span class="badge bg-danger">Khusus Super Admin</span>
            </div>
            <div class="card-body">

                <div class="alert alert-warning py-2" style="font-size:.85rem;">
                    <i class="fas fa-triangle-exclamation me-1"></i>
                    Perintah berjalan langsung di server produksi. Perintah berbahaya
                    (<code>migrate:fresh</code>, <code>db:wipe</code>, dll) otomatis diblokir.
                    Semua aktivitas tercatat di Audit Log.
                </div>

                {{-- Tombol perintah cepat --}}
                <div class="mb-3">
                    <label class="form-label fw-600 mb-2"><i class="fas fa-bolt me-1"></i> Perintah Cepat</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-primary btn-quick" data-cmd="kecamatan:sync">
                            <i class="fas fa-map me-1"></i> Sync Kecamatan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-quick" data-cmd="migrate --force">
                            <i class="fas fa-database me-1"></i> Migrate
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-quick" data-cmd="migrate:status">
                            <i class="fas fa-list-check me-1"></i> Status Migrasi
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-quick" data-cmd="db:seed --class=KecamatanDesaSeeder">
                            <i class="fas fa-seedling me-1"></i> Seed Kecamatan &amp; Desa
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-quick" data-cmd="optimize:clear">
                            <i class="fas fa-broom me-1"></i> Clear Cache
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-quick" data-cmd="config:clear">
                            <i class="fas fa-gear me-1"></i> Clear Config
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-quick" data-cmd="route:list">
                            <i class="fas fa-route me-1"></i> Route List
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-quick" data-cmd="about">
                            <i class="fas fa-circle-info me-1"></i> Info Aplikasi
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-quick" data-cmd="storage:link">
                            <i class="fas fa-link me-1"></i> Storage Link
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-quick" data-cmd="queue:restart">
                            <i class="fas fa-rotate me-1"></i> Restart Queue
                        </button>
                    </div>
                </div>

                {{-- Jendela terminal --}}
                <div class="terminal-box" id="terminalBox">
                    <div class="terminal-head">
                        <span class="dot bg-danger"></span>
                        <span class="dot bg-warning"></span>
                        <span class="dot bg-success"></span>
                        <span class="ms-2">artisan @ jang-bantah</span>
                    </div>
                    <div class="terminal-body" id="terminalOutput">
                        <div>JANG BANTAH — Web Terminal Artisan</div>
                        <div>Ketik perintah artisan lalu tekan Enter. Contoh: <span class="text-info">kecamatan:sync</span> atau <span class="text-info">php artisan migrate --force</span></div>
                        <div>Ketik <span class="text-info">clear</span> untuk membersihkan layar. Panah &#8593;/&#8595; untuk riwayat.</div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="terminal-input d-flex align-items-center">
                        <span class="prompt">php artisan&nbsp;</span>
                        <input type="text" id="terminalInput" list="artisanCommands"
                               placeholder="kecamatan:sync"
                               autocomplete="off" spellcheck="false" autofocus>
                        <datalist id="artisanCommands">
                            @foreach($commands as $c)
                                <option value="{{ $c }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .terminal-box { border-radius: 8px; overflow: hidden; border: 1px solid #333; box-shadow: 0 4px 16px rgba(0,0,0,.25); }
    .terminal-head { background: #1f2430; color: #9aa4b2; padding: 8px 12px; font-size: .8rem; display: flex; align-items: center; gap: 6px; }
    .terminal-head .dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }
    .terminal-body {
        background: #0d1117; color: #c9d1d9; font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: .82rem; line-height: 1.55; padding: 14px 16px; height: 420px; overflow-y: auto; white-space: pre-wrap; word-break: break-word;
    }
    .terminal-body .cmd { color: #79c0ff; }
    .terminal-body .err { color: #f85149; }
    .terminal-body .ok  { color: #56d364; }
    .terminal-input { background: #161b22; border-top: 1px solid #30363d; padding: 10px 16px; }
    .terminal-input .prompt { color: #56d364; font-family: Consolas, Menlo, monospace; font-size: .85rem; }
    .terminal-input input {
        flex: 1; background: transparent; border: none; outline: none; color: #c9d1d9;
        font-family: Consolas, Menlo, monospace; font-size: .85rem;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input   = document.getElementById('terminalInput');
    const output  = document.getElementById('terminalOutput');
    const TOKEN   = '{{ csrf_token() }}';
    const URL_RUN = '{{ route("admin.terminal.run") }}';
    let history   = JSON.parse(localStorage.getItem('jb_terminal_history') || '[]');
    let histIdx   = history.length;
    let running   = false;

    function print(text, cls) {
        const div = document.createElement('div');
        if (cls) div.className = cls;
        div.textContent = text;
        output.appendChild(div);
        output.scrollTop = output.scrollHeight;
    }

    function printHtml(html) {
        const div = document.createElement('div');
        div.innerHTML = html;
        output.appendChild(div);
        output.scrollTop = output.scrollHeight;
    }

    function clearScreen() {
        output.innerHTML = '';
        print('Layar dibersihkan.');
    }

    async function run(command) {
        if (running) return;
        running = true;
        input.disabled = true;

        printHtml('<span class="cmd">php artisan ' + command.replace(/</g, '&lt;') + '</span>');

        try {
            const res = await fetch(URL_RUN, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ command }),
            });
            const data = await res.json();

            if (data.cleared) { clearScreen(); }
            else {
                const cls = data.ok ? 'ok' : 'err';
                print(data.output, cls);
                if (data.ms !== undefined) print('— selesai dalam ' + data.ms + ' ms —', '');
            }
        } catch (e) {
            print('Gagal menghubungi server: ' + e.message, 'err');
        } finally {
            running = false;
            input.disabled = false;
            input.focus();
        }
    }

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            const cmd = input.value.trim();
            if (!cmd) return;
            input.value = '';
            history.push(cmd);
            if (history.length > 50) history = history.slice(-50);
            localStorage.setItem('jb_terminal_history', JSON.stringify(history));
            histIdx = history.length;
            run(cmd);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (histIdx > 0) { histIdx--; input.value = history[histIdx] ?? ''; }
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (histIdx < history.length - 1) { histIdx++; input.value = history[histIdx] ?? ''; }
            else { histIdx = history.length; input.value = ''; }
        }
    });

    document.querySelectorAll('.btn-quick').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const cmd = this.dataset.cmd;
            const label = cmd.includes('migrate --force') || cmd.includes('db:seed')
                ? 'Jalankan perintah "' + cmd + '" di server produksi?'
                : 'Jalankan perintah "' + cmd + '"?';
            if (confirm(label)) run(cmd);
        });
    });
});
</script>
@endpush
@endsection
