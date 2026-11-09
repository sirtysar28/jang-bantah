@extends('layouts.admin')
@section('page-title', 'Kelola Website')

@section('content')
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-globe me-2"></i>Kelola Konten Website Publik</span>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-external-link-alt me-1"></i>Lihat Website
        </a>
    </div>
    <div class="card-body py-2" style="font-size:.85rem;">
        <i class="fas fa-info-circle me-1 text-primary"></i>
        Semua teks & video hero pada website publik dikelola di sini.
        <span class="fw-600">Kosongkan field untuk kembali ke teks default.</span>
        Perubahan langsung tampil setelah disimpan.
    </div>
</div>

<form method="POST" action="{{ route('admin.website.update') }}" enctype="multipart/form-data">
    @csrf

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabHero" type="button"><i class="fas fa-video me-1"></i>Hero & Video</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabFitur" type="button"><i class="fas fa-star me-1"></i>Fitur</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAlur" type="button"><i class="fas fa-diagram-project me-1"></i>Aset & Alur Kerja</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabFooter" type="button"><i class="fas fa-shoe-prints me-1"></i>Footer</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabGaleri" type="button"><i class="fas fa-images me-1"></i>Galeri Kegiatan</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabVideo" type="button"><i class="fab fa-youtube me-1"></i>Video YouTube</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabBerita" type="button"><i class="fas fa-newspaper me-1"></i>Berita</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAbout" type="button"><i class="fas fa-info-circle me-1"></i>Halaman Tentang</button></li>
    </ul>

    <div class="tab-content">
        {{-- ══════════ TAB: HERO & VIDEO ══════════ --}}
        <div class="tab-pane fade show active" id="tabHero">
            <div class="card">
                <div class="card-header"><i class="fas fa-image me-2"></i>Background & Teks Hero</div>
                <div class="card-body">
                    {{-- Pilihan tipe background --}}
                    <div class="mb-4">
                        <label class="form-label fw-600">Tipe Background Hero</label>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="border rounded-3 p-3 d-flex align-items-center gap-2 h-100" style="cursor:pointer;">
                                    <input class="form-check-input mt-0" type="radio" name="web_hero_type" value="video" {{ old('web_hero_type', $content['web_hero_type']) !== 'image' ? 'checked' : '' }} onchange="toggleHeroType()">
                                    <span><i class="fas fa-video me-1 text-primary"></i><span class="fw-600">Video YouTube</span><br><small class="text-muted">video autoplay muted</small></span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="border rounded-3 p-3 d-flex align-items-center gap-2 h-100" style="cursor:pointer;">
                                    <input class="form-check-input mt-0" type="radio" name="web_hero_type" value="image" {{ old('web_hero_type', $content['web_hero_type']) === 'image' ? 'checked' : '' }} onchange="toggleHeroType()">
                                    <span><i class="fas fa-image me-1 text-primary"></i><span class="fw-600">Gambar</span><br><small class="text-muted">upload foto statis</small></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Field khusus tipe VIDEO --}}
                        <div id="heroVideoFields">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Video YouTube (URL atau ID)</label>
                                    <input type="text" name="web_hero_video" class="form-control" value="{{ old('web_hero_video', $content['web_hero_video']) }}" placeholder="https://www.youtube.com/watch?v=...">
                                    <small class="text-muted">Tempel URL video YouTube apa pun (watch / youtu.be / embed) atau ID 11 karakter. Video diputar otomatis <em>muted</em> sebagai background hero.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Pratinjau Video Saat Ini</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://img.youtube.com/vi/{{ \App\WebsiteContent::youtubeId() }}/hqdefault.jpg" alt="Pratinjau video" style="width:150px;border-radius:8px;border:1px solid #dee2e6;">
                                        <div style="font-size:.8rem;">
                                            <div><span class="badge bg-primary">ID: {{ \App\WebsiteContent::youtubeId() }}</span></div>
                                            <a href="https://www.youtube.com/watch?v={{ \App\WebsiteContent::youtubeId() }}" target="_blank" class="mt-1 d-inline-block"><i class="fas fa-external-link-alt me-1"></i>Buka di YouTube</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Field khusus tipe GAMBAR (multi-upload → slider otomatis) --}}
                        <div id="heroImageFields" class="d-none">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Upload Gambar (bisa pilih beberapa sekaligus)</label>
                                    <input type="file" name="web_hero_image[]" class="form-control" accept="image/jpeg,image/png,image/webp" multiple onchange="previewHeroImages(this)">
                                    <small class="text-muted">Format JPG / PNG / WebP, tiap file maks 5MB. <b>Upload lebih dari 1 gambar → otomatis menjadi slider hero</b> dengan transisi halus. Gambar baru ditambahkan setelah yang sudah ada.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Gambar Saat Ini (@count($heroPaths) gambar)</label>
                                    @if (count($heroPaths) > 0)
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @foreach ($heroPaths as $i => $path)
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $path) }}" alt="Gambar hero {{ $i + 1 }}" style="width:120px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                                            @if (count($heroPaths) > 1 && $i === 0)
                                            <div style="font-size:.65rem;" class="text-muted mt-1">slide pertama</div>
                                            @endif
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" name="hapus_gambar[]" value="{{ $path }}" id="hapusGambar{{ $i }}">
                                                <label class="form-check-label text-danger" for="hapusGambar{{ $i }}" style="font-size:.75rem;">hapus</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">Centang "hapus" lalu klik <b>Simpan</b> untuk menghapus gambar terpilih.</small>
                                    @else
                                    <div class="border rounded-3 p-4 text-center text-muted" style="font-size:.85rem;">
                                        <i class="fas fa-image d-block mb-2" style="font-size:1.5rem;"></i>Belum ada gambar — jika tipe "Gambar" dipilih tanpa gambar, background gradient biru yang digunakan.
                                    </div>
                                    @endif

                                    {{-- Pratinjau file yang baru dipilih --}}
                                    <div id="heroImagesPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6"><label class="form-label fw-600">Teks Badge (kecil di atas judul)</label><input type="text" name="web_hero_badge" class="form-control" value="{{ old('web_hero_badge', $content['web_hero_badge']) }}"></div>
                        <div class="col-md-6"><label class="form-label fw-600">Judul Besar</label><input type="text" name="web_hero_title" class="form-control" value="{{ old('web_hero_title', $content['web_hero_title']) }}"></div>
                        <div class="col-md-6"><label class="form-label fw-600">Subjudul</label><input type="text" name="web_hero_subtitle" class="form-control" value="{{ old('web_hero_subtitle', $content['web_hero_subtitle']) }}"></div>
                        <div class="col-md-3"><label class="form-label fw-600">Teks Tombol 1</label><input type="text" name="web_hero_btn1_text" class="form-control" value="{{ old('web_hero_btn1_text', $content['web_hero_btn1_text']) }}"></div>
                        <div class="col-md-3"><label class="form-label fw-600">Teks Tombol 2</label><input type="text" name="web_hero_btn2_text" class="form-control" value="{{ old('web_hero_btn2_text', $content['web_hero_btn2_text']) }}"></div>
                        <div class="col-12"><label class="form-label fw-600">Deskripsi</label><textarea name="web_hero_description" rows="3" class="form-control">{{ old('web_hero_description', $content['web_hero_description']) }}</textarea></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ TAB: FITUR ══════════ --}}
        <div class="tab-pane fade" id="tabFitur">
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-heading me-2"></i>Judul Section Fitur</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-600">Judul</label><input type="text" name="web_fitur_title" class="form-control" value="{{ old('web_fitur_title', $content['web_fitur_title']) }}"></div>
                        <div class="col-md-8"><label class="form-label fw-600">Deskripsi</label><input type="text" name="web_fitur_desc" class="form-control" value="{{ old('web_fitur_desc', $content['web_fitur_desc']) }}"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><i class="fas fa-list me-2"></i>4 Kartu Fitur</div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach (['web_fitur1_title' => 'web_fitur1_desc', 'web_fitur2_title' => 'web_fitur2_desc', 'web_fitur3_title' => 'web_fitur3_desc', 'web_fitur4_title' => 'web_fitur4_desc'] as $tKey => $dKey)
                        @php $no = substr($tKey, 9, 1); @endphp
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-600 mb-2 text-primary"><i class="fas fa-{{ ['database', 'certificate', 'shield-halved', 'globe'][$no - 1] }} me-1"></i>Fitur {{ $no }}</div>
                                <label class="form-label">Judul</label>
                                <input type="text" name="{{ $tKey }}" class="form-control mb-2" value="{{ old($tKey, $content[$tKey]) }}">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="{{ $dKey }}" rows="2" class="form-control">{{ old($dKey, $content[$dKey]) }}</textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ TAB: ASET & ALUR KERJA ══════════ --}}
        <div class="tab-pane fade" id="tabAlur">
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-map-marked-alt me-2"></i>Section Data Aset Terbaru</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-600">Judul</label><input type="text" name="web_aset_title" class="form-control" value="{{ old('web_aset_title', $content['web_aset_title']) }}"></div>
                        <div class="col-md-8"><label class="form-label fw-600">Deskripsi</label><input type="text" name="web_aset_desc" class="form-control" value="{{ old('web_aset_desc', $content['web_aset_desc']) }}"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><i class="fas fa-diagram-project me-2"></i>Section Alur Kerja</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-600">Judul</label><input type="text" name="web_alur_title" class="form-control" value="{{ old('web_alur_title', $content['web_alur_title']) }}"></div>
                        <div class="col-md-8"><label class="form-label fw-600">Deskripsi</label><input type="text" name="web_alur_desc" class="form-control" value="{{ old('web_alur_desc', $content['web_alur_desc']) }}"></div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Langkah-langkah (satu baris = satu langkah)</label>
                            <textarea name="web_alur_steps" rows="10" class="form-control" placeholder="Pendataan Aset&#10;Validasi Data&#10;...">{{ old('web_alur_steps', $content['web_alur_steps']) }}</textarea>
                            <small class="text-muted">Nomor urut ditampilkan otomatis.</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="border rounded-3 p-3 w-100" style="background:#f8fafc;font-size:.85rem;">
                                <div class="fw-600 mb-2"><i class="fas fa-eye me-1"></i>Pratinjau:</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach (\App\WebsiteContent::lines('web_alur_steps') as $i => $step)
                                    <span class="badge" style="background:var(--jb-primary,#1B4965);">{{ $i + 1 }}. {{ $step }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ TAB: FOOTER ══════════ --}}
        <div class="tab-pane fade" id="tabFooter">
            <div class="card">
                <div class="card-header"><i class="fas fa-shoe-prints me-2"></i>Konten Footer Website</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-600">Deskripsi Singkat</label><textarea name="web_footer_desc" rows="3" class="form-control">{{ old('web_footer_desc', $content['web_footer_desc']) }}</textarea></div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Alamat (satu baris = satu baris alamat)</label>
                            <textarea name="web_footer_address" rows="3" class="form-control">{{ old('web_footer_address', $content['web_footer_address']) }}</textarea>
                            <label class="form-label fw-600 mt-2">Nama Instansi</label>
                            <input type="text" name="web_footer_instansi" class="form-control" value="{{ old('web_footer_instansi', $content['web_footer_instansi']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Website Pemda (URL)</label>
                            <input type="text" name="web_footer_website" class="form-control" value="{{ old('web_footer_website', $content['web_footer_website']) }}">
                            <label class="form-label fw-600 mt-2">Teks Copyright</label>
                            <input type="text" name="web_footer_copyright" class="form-control" value="{{ old('web_footer_copyright', $content['web_footer_copyright']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Link Terkait</label>
                            @foreach (range(1, 3) as $n)
                            <div class="row g-2 mb-2">
                                <div class="col-5"><input type="text" name="web_footer_link{{ $n }}_name" class="form-control form-control-sm" placeholder="Nama link {{ $n }}" value="{{ old('web_footer_link' . $n . '_name', $content['web_footer_link' . $n . '_name']) }}"></div>
                                <div class="col-7"><input type="text" name="web_footer_link{{ $n }}_url" class="form-control form-control-sm" placeholder="https://..." value="{{ old('web_footer_link' . $n . '_url', $content['web_footer_link' . $n . '_url']) }}"></div>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-600">Media Sosial (Icon + URL)</label>
                                <button type="button" class="btn btn-sm btn-primary mb-1" onclick="addSocialRow()"><i class="fas fa-plus me-1"></i>Tambah</button>
                            </div>
                            <div id="socialRows" class="d-flex flex-column gap-2 mb-1">
                                @if (count($socialLinks) > 0)
                                    @foreach ($socialLinks as $link)
                                    <div class="row g-2 align-items-center social-row">
                                        <div class="col-5">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text social-icon-preview"><i class="{{ $link['icon'] }}"></i></span>
                                                <select name="social_icon[]" class="form-select social-icon-select">@foreach (\App\WebsiteContent::socialIconOptions() as $ico => $label)<option value="{{ $ico }}" {{ $ico === $link['icon'] ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="social_url[]" class="form-control form-control-sm" placeholder="https://..." value="{{ $link['url'] }}">
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeSocialRow(this)" title="Hapus baris"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-muted" style="font-size:.85rem;" id="socialEmptyInfo">Belum ada media sosial. Klik <b>Tambah</b> untuk membuat baru.</div>
                                @endif
                            </div>
                            <small class="text-muted">Jumlah media sosial tidak dibatasi — pilih icon lalu isi URL (contoh: https://instagram.com/halbarkab). Icon bisa dipilih berbeda-beda untuk tiap baris.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ TAB: GALERI KEGIATAN ══════════ --}}
        <div class="tab-pane fade" id="tabGaleri">
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-heading me-2"></i>Judul Section Galeri</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-600">Judul</label><input type="text" name="web_galeri_title" class="form-control" value="{{ old('web_galeri_title', $content['web_galeri_title']) }}"></div>
                        <div class="col-md-8"><label class="form-label fw-600">Deskripsi</label><input type="text" name="web_galeri_desc" class="form-control" value="{{ old('web_galeri_desc', $content['web_galeri_desc']) }}"></div>
                    </div>
                    <small class="text-muted d-block mt-1">Section ini tampil di halaman depan, tepat di bawah strip statistik. <b>Kosong/0 foto = section otomatis disembunyikan.</b></small>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><i class="fas fa-images me-2"></i>Foto Kegiatan (@count($galeriPaths) foto)</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-600">Upload Foto (bisa pilih beberapa sekaligus)</label>
                            <input type="file" name="galeri_foto[]" class="form-control" accept="image/jpeg,image/png,image/webp" multiple onchange="previewGaleri(this)">
                            <small class="text-muted">Format JPG / PNG / WebP, tiap file maks 5MB. Jumlah tidak dibatasi — foto baru ditambahkan setelah yang sudah ada.</small>
                            <div id="galeriPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-600">Foto Saat Ini (@count($galeriPaths) foto)</label>
                            @if (count($galeriPaths) > 0)
                            <div class="d-flex flex-wrap gap-2" style="max-height:340px;overflow:auto;padding:4px;">
                                @foreach ($galeriPaths as $i => $path)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $path) }}" alt="Foto kegiatan {{ $i + 1 }}" style="width:110px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="hapus_galeri[]" value="{{ $path }}" id="hapusGaleri{{ $i }}">
                                        <label class="form-check-label text-danger" for="hapusGaleri{{ $i }}" style="font-size:.75rem;">hapus</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Centang "hapus" lalu klik <b>Simpan</b> untuk menghapus foto terpilih.</small>
                            @else
                            <div class="border rounded-3 p-4 text-center text-muted" style="font-size:.85rem;">
                                <i class="fas fa-images d-block mb-2" style="font-size:1.5rem;"></i>Belum ada foto — upload foto kegiatan JANG BANTAH untuk ditampilkan di halaman depan.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ TAB: VIDEO YOUTUBE ══════════ --}}
        <div class="tab-pane fade" id="tabVideo">
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-heading me-2"></i>Judul Section Video</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-600">Judul</label><input type="text" name="web_video_title" class="form-control" value="{{ old('web_video_title', $content['web_video_title']) }}"></div>
                        <div class="col-md-8"><label class="form-label fw-600">Deskripsi</label><input type="text" name="web_video_desc" class="form-control" value="{{ old('web_video_desc', $content['web_video_desc']) }}"></div>
                    </div>
                    <small class="text-muted d-block mt-1">Section ini tampil di halaman depan, tepat di bawah galeri kegiatan. Halaman depan menampilkan <b>maksimal 3 thumbnail</b> — lebih dari itu otomatis muncul tombol menuju halaman khusus <b>/video</b> yang memuat semua video.</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fab fa-youtube me-2"></i>Link Video / Channel YouTube ({{ count($videoLinks) }} link)</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addVideoRow()"><i class="fas fa-plus me-1"></i>Tambah Link</button>
                </div>
                <div class="card-body">
                    <div id="videoRows" class="d-flex flex-column gap-2 mb-1">
                        @if (count($videoLinks) > 0)
                            @foreach ($videoLinks as $link)
                            <div class="row g-2 align-items-center video-row">
                                <div class="col-md-9">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fab fa-youtube text-danger"></i></span>
                                        <input type="text" name="video_url[]" class="form-control" placeholder="https://youtu.be/... atau https://youtube.com/@channel" value="{{ $link }}" oninput="previewVideoRow(this)">
                                    </div>
                                </div>
                                <div class="col-md-2 video-thumb-cell">
                                    @php $vid = \App\WebsiteContent::extractYoutubeId($link); @endphp
                                    @if ($vid)
                                    <img src="https://img.youtube.com/vi/{{ $vid }}/default.jpg" alt="Thumbnail" class="video-thumb-img" style="width:64px;height:36px;object-fit:cover;border-radius:5px;border:1px solid #dee2e6;">
                                    @elseif (\App\WebsiteContent::isYoutubeChannelUrl($link))
                                    <span class="badge bg-danger"><i class="fab fa-youtube me-1"></i>Channel</span>
                                    @else
                                    <span class="badge bg-secondary">?</span>
                                    @endif
                                </div>
                                <div class="col-md-1 d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVideoRow(this)" title="Hapus baris"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-muted" style="font-size:.85rem;" id="videoEmptyInfo">Belum ada video. Klik <b>Tambah Link</b> lalu tempel link video YouTube (contoh: https://youtu.be/hJNMnZ_XpBE) atau link channel (contoh: https://youtube.com/@jangbantah_bkadhalbar).</div>
                        @endif
                    </div>
                    <small class="text-muted">Jumlah link <b>tidak dibatasi</b>. Link video apa pun (watch / youtu.be / embed) tampil sebagai thumbnail — klik play = popup video. Link channel (@handle) tampil sebagai kartu channel yang membuka tab baru. Link yang tidak dikenali otomatis diabaikan saat disimpan.</small>
                </div>
            </div>
        </div>

        {{-- ══════════ TAB: BERITA ══════════ --}}
        <div class="tab-pane fade" id="tabBerita">
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-heading me-2"></i>Judul Section Berita</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-600">Judul</label><input type="text" name="web_berita_title" class="form-control" value="{{ old('web_berita_title', $content['web_berita_title']) }}"></div>
                        <div class="col-md-8"><label class="form-label fw-600">Deskripsi</label><input type="text" name="web_berita_desc" class="form-control" value="{{ old('web_berita_desc', $content['web_berita_desc']) }}"></div>
                    </div>
                    <small class="text-muted d-block mt-1">Section ini tampil di <b>paling bawah halaman depan (sebelum footer)</b>: 3 berita terbaru + tombol "Berita Lainnya" menuju halaman khusus <b>/berita</b> yang memuat semua berita. Judul & deskripsi section di sini hanya tampil setelah klik <b>Simpan Semua Perubahan</b>.</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-newspaper me-2"></i>Daftar Berita ({{ count($beritas) }} berita)</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openBeritaModal()"><i class="fas fa-plus me-1"></i>Tambah Berita</button>
                </div>
                <div class="card-body">
                    @if (count($beritas) > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach ($beritas as $berita)
                        <div class="border rounded-3 p-2 p-md-3 d-flex gap-3 align-items-start flex-wrap flex-md-nowrap">
                            @if ($berita->gambar)
                            <img src="{{ $berita->gambarUrl() }}" alt="{{ $berita->judul }}" style="width:130px;height:85px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;flex:0 0 auto;">
                            @else
                            <div class="d-flex align-items-center justify-content-center" style="width:130px;height:85px;border-radius:8px;background:var(--blue-50,#f0f7ff);flex:0 0 auto;"><i class="fas fa-newspaper text-muted" style="font-size:1.6rem;opacity:.5;"></i></div>
                            @endif
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="fw-600 text-primary">{{ $berita->judul }}</div>
                                <div class="text-muted" style="font-size:.78rem;"><i class="fas fa-calendar-alt me-1"></i>{{ $berita->tanggalIndo() }}</div>
                                <p class="mb-0 mt-1" style="font-size:.82rem;">{{ \Illuminate\Support\Str::limit(strip_tags((string) $berita->deskripsi), 160) }}</p>
                            </div>
                            <div class="d-flex gap-1 flex-md-column" style="flex:0 0 auto;">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openBeritaModal(this)"
                                    data-id="{{ $berita->id }}" data-judul="{{ $berita->judul }}"
                                    data-deskripsi="{{ $berita->deskripsi }}" data-gambar="{{ $berita->gambarUrl() }}"
                                    title="Edit berita"><i class="fas fa-pen me-1"></i>Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBerita(this)"
                                    data-id="{{ $berita->id }}" data-judul="{{ $berita->judul }}"
                                    title="Hapus berita"><i class="fas fa-trash me-1"></i>Hapus</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted d-block mt-3">Jumlah berita <b>tidak dibatasi</b> — berita terbaru otomatis tampil paling depan. Perubahan daftar berita langsung tersimpan (tidak perlu klik "Simpan Semua Perubahan").</small>
                    @else
                    <div class="border rounded-3 p-4 text-center text-muted" style="font-size:.85rem;">
                        <i class="fas fa-newspaper d-block mb-2" style="font-size:1.5rem;"></i>Belum ada berita. Klik <b>Tambah Berita</b> untuk membuat berita pertama — 3 berita terbaru otomatis tampil di halaman depan.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════ TAB: HALAMAN TENTANG ══════════ --}}
        <div class="tab-pane fade" id="tabAbout">
            <div class="card">
                <div class="card-header"><i class="fas fa-info-circle me-2"></i>Halaman "Tentang"</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label fw-600">Teks Badge</label><input type="text" name="web_about_badge" class="form-control" value="{{ old('web_about_badge', $content['web_about_badge']) }}"></div>
                        <div class="col-md-5"><label class="form-label fw-600">Judul Halaman</label><input type="text" name="web_about_title" class="form-control" value="{{ old('web_about_title', $content['web_about_title']) }}"></div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">Kotak Gradient (kiri)</label>
                            <input type="text" name="web_about_box_title" class="form-control mb-2" placeholder="Judul kotak" value="{{ old('web_about_box_title', $content['web_about_box_title']) }}">
                            <input type="text" name="web_about_box_text" class="form-control" placeholder="Subjudul kotak" value="{{ old('web_about_box_text', $content['web_about_box_text']) }}">
                        </div>
                        <div class="col-md-6"><label class="form-label fw-600">Paragraf 1</label><textarea name="web_about_p1" rows="4" class="form-control">{{ old('web_about_p1', $content['web_about_p1']) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label fw-600">Paragraf 2</label><textarea name="web_about_p2" rows="4" class="form-control">{{ old('web_about_p2', $content['web_about_p2']) }}</textarea></div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Judul Daftar Tujuan</label>
                            <input type="text" name="web_about_tujuan_title" class="form-control mb-2" value="{{ old('web_about_tujuan_title', $content['web_about_tujuan_title']) }}">
                            <label class="form-label fw-600">Daftar Tujuan (satu baris = satu poin)</label>
                            <textarea name="web_about_tujuan" rows="7" class="form-control">{{ old('web_about_tujuan', $content['web_about_tujuan']) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Visi</label>
                            <textarea name="web_about_visi" rows="3" class="form-control mb-2">{{ old('web_about_visi', $content['web_about_visi']) }}</textarea>
                            <label class="form-label fw-600">Misi (satu baris = satu poin)</label>
                            <textarea name="web_about_misi" rows="5" class="form-control">{{ old('web_about_misi', $content['web_about_misi']) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body d-flex justify-content-end gap-2">
            <a href="{{ route('admin.website.index') }}" class="btn btn-outline-secondary"><i class="fas fa-undo me-1"></i>Reset Form</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Semua Perubahan</button>
        </div>
    </div>
</form>

{{-- ══════════ MODAL TAMBAH/EDIT BERITA (di luar form utama — punya form sendiri) ══════════ --}}
<div class="modal fade" id="beritaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.website.berita.store') }}" enctype="multipart/form-data" id="beritaForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="beritaModalTitle"><i class="fas fa-plus me-2"></i>Tambah Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="beritaJudul" class="form-control" maxlength="255" placeholder="Contoh: Sosialisasi Pendataan Aset Tanah Kecamatan Jailolo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Deskripsi</label>
                        <textarea name="deskripsi" id="beritaDeskripsi" rows="5" class="form-control" placeholder="Isi / ringkasan berita..."></textarea>
                        <small class="text-muted">Maks 5.000 karakter. Di halaman depan hanya cuplikan singkat yang tampil — teks lengkap tampil di halaman Berita.</small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-600">Gambar Berita</label>
                        <input type="file" name="gambar" id="beritaGambar" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewBeritaGambar(this)">
                        <small class="text-muted">Format JPG / PNG / WebP, maks 5MB (opsional).</small>
                    </div>
                    <div id="beritaGambarWrap" class="d-none mt-2">
                        <img id="beritaGambarImg" src="" alt="Pratinjau gambar berita" style="max-width:100%;max-height:220px;object-fit:contain;border-radius:8px;border:1px solid #dee2e6;">
                        <div class="form-check mt-1 d-none" id="beritaGambarKeepWrap">
                            <input class="form-check-input" type="checkbox" id="beritaGambarKeepInfo" checked disabled>
                            <label class="form-check-label text-muted" for="beritaGambarKeepInfo" style="font-size:.8rem;">Gambar lama dipertahankan bila tidak memilih file baru.</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Batal</button>
                    <button type="submit" class="btn btn-primary" id="beritaSubmitBtn"><i class="fas fa-save me-1"></i>Simpan Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Form hapus berita (dipicu tombol Hapus via JS) --}}
<form method="POST" action="{{ route('admin.website.berita.destroy', 0) }}" id="beritaDeleteForm" class="d-none">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    /* ── Modal Tambah/Edit Berita ── */
    var beritaModalEl = document.getElementById('beritaModal');
    var beritaModal = null;
    if (beritaModalEl && window.bootstrap) {
        beritaModal = new bootstrap.Modal(beritaModalEl);
    }

    function openBeritaModal(btn) {
        var form   = document.getElementById('beritaForm');
        var judul  = document.getElementById('beritaJudul');
        var desk   = document.getElementById('beritaDeskripsi');
        var file   = document.getElementById('beritaGambar');
        var wrap   = document.getElementById('beritaGambarWrap');
        var img    = document.getElementById('beritaGambarImg');
        var keepW  = document.getElementById('beritaGambarKeepWrap');
        var title  = document.getElementById('beritaModalTitle');
        var submit = document.getElementById('beritaSubmitBtn');

        file.value = '';

        var data = btn && btn.dataset && btn.dataset.id ? btn.dataset : null;

        if (data) {
            // Mode edit → arahkan form ke URL update berita terkait
            form.action = form.action.replace(/\/berita\/?$/, '/berita/' + data.id);
            judul.value = data.judul || '';
            desk.value  = data.deskripsi || '';
            if (data.gambar) {
                wrap.classList.remove('d-none');
                keepW.classList.remove('d-none');
                img.src = data.gambar;
            } else {
                wrap.classList.add('d-none');
                keepW.classList.add('d-none');
            }
            title.innerHTML = '<i class="fas fa-pen me-2"></i>Edit Berita';
            submit.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Perubahan';
        } else {
            // Mode tambah → pastikan kembali ke URL store
            form.action = form.action.replace(/\/berita\/\d+$/, '/berita');
            judul.value = '';
            desk.value  = '';
            wrap.classList.add('d-none');
            keepW.classList.add('d-none');
            title.innerHTML = '<i class="fas fa-plus me-2"></i>Tambah Berita';
            submit.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Berita';
        }

        if (beritaModal) beritaModal.show();
    }

    function previewBeritaGambar(input) {
        var wrap = document.getElementById('beritaGambarWrap');
        var img  = document.getElementById('beritaGambarImg');
        var keepW = document.getElementById('beritaGambarKeepWrap');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                wrap.classList.remove('d-none');
                keepW.classList.add('d-none'); // sedang ganti gambar baru
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function hapusBerita(btn) {
        var id = btn.dataset.id, judul = btn.dataset.judul || '';
        if (!confirm('Hapus berita "' + judul + '"?\nTindakan ini tidak dapat dibatalkan.')) return;
        var form = document.getElementById('beritaDeleteForm');
        form.action = form.action.replace(/\/berita\/?\d*$/, '/berita/' + id);
        form.submit();
    }

    /* Toggle tampilan field berdasarkan tipe background hero */
    function toggleHeroType() {
        var type = document.querySelector('input[name="web_hero_type"]:checked').value;
        document.getElementById('heroVideoFields').classList.toggle('d-none', type !== 'video');
        document.getElementById('heroImageFields').classList.toggle('d-none', type !== 'image');
    }
    document.addEventListener('DOMContentLoaded', toggleHeroType);

    /* ── Media sosial dinamis: tambah baris icon + URL ── */
    var socialIconOptions = @json(\App\WebsiteContent::socialIconOptions());

    function addSocialRow(icon, url) {
        var info = document.getElementById('socialEmptyInfo');
        if (info) info.remove();

        var row = document.createElement('div');
        row.className = 'row g-2 align-items-center social-row';

        var optionsHtml = '';
        for (var ico in socialIconOptions) {
            optionsHtml += '<option value="' + ico + '"' + (ico === (icon || 'fab fa-facebook-f') ? ' selected' : '') + '>' + socialIconOptions[ico] + '</option>';
        }

        row.innerHTML =
            '<div class="col-5"><div class="input-group input-group-sm">' +
            '<span class="input-group-text social-icon-preview"><i class="' + (icon || 'fab fa-facebook-f') + '"></i></span>' +
            '<select name="social_icon[]" class="form-select social-icon-select">' + optionsHtml + '</select></div></div>' +
            '<div class="col-6"><input type="text" name="social_url[]" class="form-control form-control-sm" placeholder="https://..." value="' + (url || '') + '"></div>' +
            '<div class="col-1"><button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeSocialRow(this)" title="Hapus baris"><i class="fas fa-times"></i></button></div>';

        document.getElementById('socialRows').appendChild(row);
    }

    function removeSocialRow(btn) {
        btn.closest('.social-row').remove();
    }

    /* ── Link video YouTube dinamis: tambah baris + pratinjau thumbnail ── */
    function parseYoutubeId(url) {
        url = (url || '').trim();
        if (!url) return null;
        if (/^[\w-]{11}$/.test(url)) return url;
        var m = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([\w-]{11})/i);
        return m ? m[1] : null;
    }
    function isYoutubeChannel(url) {
        return /^https?:\/\/(www\.)?youtube\.com\/(@[\w.\-]+|channel\/[\w-]+|c\/[\w.\-]+)/i.test((url || '').trim());
    }

    function updateVideoThumbCell(cell, url) {
        var id = parseYoutubeId(url);
        if (id) {
            cell.innerHTML = '<img src="https://img.youtube.com/vi/' + id + '/default.jpg" alt="Thumbnail" style="width:64px;height:36px;object-fit:cover;border-radius:5px;border:1px solid #dee2e6;">';
        } else if (isYoutubeChannel(url)) {
            cell.innerHTML = '<span class="badge bg-danger"><i class="fab fa-youtube me-1"></i>Channel</span>';
        } else if (url.trim() !== '') {
            cell.innerHTML = '<span class="badge bg-warning text-dark" title="Link belum dikenali">tidak valid</span>';
        } else {
            cell.innerHTML = '<span class="badge bg-secondary">?</span>';
        }
    }

    function previewVideoRow(input) {
        var cell = input.closest('.video-row')?.querySelector('.video-thumb-cell');
        if (cell) updateVideoThumbCell(cell, input.value);
    }

    function addVideoRow(url) {
        var info = document.getElementById('videoEmptyInfo');
        if (info) info.remove();

        var row = document.createElement('div');
        row.className = 'row g-2 align-items-center video-row';
        row.innerHTML =
            '<div class="col-md-9"><div class="input-group input-group-sm">' +
            '<span class="input-group-text"><i class="fab fa-youtube text-danger"></i></span>' +
            '<input type="text" name="video_url[]" class="form-control" placeholder="https://youtu.be/... atau https://youtube.com/@channel" value="' + (url || '') + '" oninput="previewVideoRow(this)"></div></div>' +
            '<div class="col-md-2 video-thumb-cell"></div>' +
            '<div class="col-md-1 d-flex justify-content-end"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVideoRow(this)" title="Hapus baris"><i class="fas fa-times"></i></button></div>';

        document.getElementById('videoRows').appendChild(row);
        if (url) updateVideoThumbCell(row.querySelector('.video-thumb-cell'), url);
    }

    function removeVideoRow(btn) {
        var wrap = document.getElementById('videoRows');
        btn.closest('.video-row').remove();
        if (wrap && !wrap.querySelector('.video-row')) {
            wrap.innerHTML = '<div class="text-muted" style="font-size:.85rem;" id="videoEmptyInfo">Belum ada video. Klik <b>Tambah Link</b> lalu tempel link video YouTube (contoh: https://youtu.be/hJNMnZ_XpBE) atau link channel (contoh: https://youtube.com/@jangbantah_bkadhalbar).</div>';
        }
    }

    /* Update preview icon ketika pilihan icon diubah */
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('social-icon-select')) {
            var preview = e.target.closest('.social-row').querySelector('.social-icon-preview i');
            if (preview) preview.className = e.target.value;
        }
    });

    /* Pratinjau semua gambar galeri kegiatan yang baru dipilih */
    function previewGaleri(input) {
        var container = document.getElementById('galeriPreview');
        if (!container) return;
        container.innerHTML = '';
        if (!input.files || input.files.length === 0) return;

        Array.from(input.files).forEach(function (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:110px;height:80px;object-fit:cover;border-radius:6px;border:1px dashed #198754;';
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    /* Pratinjau semua gambar yang baru dipilih sebelum disimpan */
    function previewHeroImages(input) {
        var container = document.getElementById('heroImagesPreview');
        if (!container) return;
        container.innerHTML = '';
        if (!input.files || input.files.length === 0) return;

        Array.from(input.files).forEach(function (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var wrap = document.createElement('div');
                wrap.className = 'text-muted';
                wrap.style.cssText = 'font-size:.65rem;';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:120px;height:70px;object-fit:cover;border-radius:6px;border:1px dashed #0d6efd;';
                wrap.appendChild(img);
                container.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush
@endsection
