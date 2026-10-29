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

                        {{-- Field khusus tipe GAMBAR --}}
                        <div id="heroImageFields" class="d-none">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Upload Gambar Baru</label>
                                    <input type="file" name="web_hero_image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewHeroImage(this)">
                                    <small class="text-muted">Format JPG / PNG / WebP, maks 5MB. Disarankan gambar resolusi tinggi (mis. 1920×1080) karena ditampilkan layar penuh.</small>
                                    @if (\App\WebsiteContent::heroImagePath())
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="hapus_gambar" value="1" id="hapusGambar">
                                        <label class="form-check-label" for="hapusGambar" style="font-size:.85rem;">Hapus gambar (kembali ke background gradient)</label>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Gambar Saat Ini</label>
                                    @if (\App\WebsiteContent::heroImageUrl())
                                    <img id="heroImagePreview" src="{{ \App\WebsiteContent::heroImageUrl() }}" alt="Gambar hero" style="width:100%;max-width:280px;border-radius:8px;border:1px solid #dee2e6;object-fit:cover;">
                                    @else
                                    <div id="heroImagePreview" class="border rounded-3 p-4 text-center text-muted" style="font-size:.85rem;max-width:280px;">
                                        <i class="fas fa-image d-block mb-2" style="font-size:1.5rem;"></i>Belum ada gambar — jika tipe "Gambar" dipilih tanpa gambar, background gradient biru yang digunakan.
                                    </div>
                                    @endif
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
                            <label class="form-label fw-600">Media Sosial (URL)</label>
                            <div class="row g-2">
                                <div class="col-4"><div class="input-group input-group-sm"><span class="input-group-text"><i class="fab fa-facebook-f"></i></span><input type="text" name="web_social_facebook" class="form-control" value="{{ old('web_social_facebook', $content['web_social_facebook']) }}"></div></div>
                                <div class="col-4"><div class="input-group input-group-sm"><span class="input-group-text"><i class="fab fa-instagram"></i></span><input type="text" name="web_social_instagram" class="form-control" value="{{ old('web_social_instagram', $content['web_social_instagram']) }}"></div></div>
                                <div class="col-4"><div class="input-group input-group-sm"><span class="input-group-text"><i class="fab fa-youtube"></i></span><input type="text" name="web_social_youtube" class="form-control" value="{{ old('web_social_youtube', $content['web_social_youtube']) }}"></div></div>
                            </div>
                        </div>
                    </div>
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

@push('scripts')
<script>
    /* Toggle tampilan field berdasarkan tipe background hero */
    function toggleHeroType() {
        var type = document.querySelector('input[name="web_hero_type"]:checked').value;
        document.getElementById('heroVideoFields').classList.toggle('d-none', type !== 'video');
        document.getElementById('heroImageFields').classList.toggle('d-none', type !== 'image');
    }
    document.addEventListener('DOMContentLoaded', toggleHeroType);

    /* Pratinjau gambar yang baru dipilih sebelum disimpan */
    function previewHeroImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var el = document.getElementById('heroImagePreview');
                if (el.tagName === 'IMG') {
                    el.src = e.target.result;
                } else {
                    var img = document.createElement('img');
                    img.id = 'heroImagePreview';
                    img.src = e.target.result;
                    img.style.cssText = 'width:100%;max-width:280px;border-radius:8px;border:1px solid #dee2e6;object-fit:cover;';
                    el.replaceWith(img);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
