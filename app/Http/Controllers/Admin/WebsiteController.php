<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Kelola konten website publik (hero video, teks-teks halaman depan,
 * halaman Tentang, dan footer) — khusus Super Admin.
 */
class WebsiteController extends Controller
{
    /**
     * Halaman form pengelolaan konten website.
     */
    public function index()
    {
        $content = [];
        foreach (array_keys(WebsiteContent::defaults()) as $key) {
            $content[$key] = WebsiteContent::get($key);
        }

        // Media sosial dinamis (icon + URL, jumlah tidak dibatasi)
        $socialLinks = WebsiteContent::socialLinks();

        // Daftar gambar hero (multi-upload → slider)
        $heroPaths = WebsiteContent::heroImagePaths();

        // Daftar foto galeri kegiatan (multi-upload)
        $galeriPaths = WebsiteContent::galeriPhotoPaths();

        return view('admin.website.index', compact('content', 'socialLinks', 'heroPaths', 'galeriPaths'));
    }

    /**
     * Simpan seluruh konten website.
     * Field yang dikosongkan otomatis kembali ke nilai default.
     */
    public function update(Request $request)
    {
        $defaults = WebsiteContent::defaults();

        // web_hero_image/web_hero_images & web_galeri_fotos ditangani terpisah sebagai upload file,
        // web_social_links ditangani terpisah sebagai daftar dinamis (icon + URL)
        $fields = array_values(array_diff(
            array_keys($defaults),
            ['web_hero_image', 'web_hero_images', 'web_galeri_fotos', 'web_social_links']
        ));

        $rules = collect($fields)->mapWithKeys(fn ($f) => [$f => 'nullable|string|max:3000'])->all();
        $rules['web_hero_type'] = 'nullable|in:video,image';
        $rules['web_hero_image']   = 'array'; // multi-upload: bisa lebih dari satu gambar
        $rules['web_hero_image.*'] = 'image|mimes:jpg,jpeg,png,webp|max:5120'; // tiap file maks 5MB
        $rules['hapus_gambar']   = 'nullable|array'; // daftar path gambar yang dicentang untuk dihapus
        $rules['galeri_foto']    = 'array'; // multi-upload foto galeri kegiatan
        $rules['galeri_foto.*']  = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        $rules['hapus_galeri']   = 'nullable|array';

        $validated = $request->validate($rules);

        foreach ($fields as $key) {
            if (! array_key_exists($key, $validated)) {
                continue;
            }

            $value = str_replace(["\r\n", "\r"], "\n", $validated[$key]);
            $value = trim($value);

            // Video hero: terima URL YouTube apa pun, simpan sebagai ID video
            if ($key === 'web_hero_video') {
                $value = WebsiteContent::extractYoutubeId($value)
                    ?? $defaults['web_hero_video'];
            }

            Setting::set($key, $value);
        }

        // ── Media sosial dinamis: susun array icon+URL lalu simpan sebagai JSON ──
        $icons = (array) $request->input('social_icon', []);
        $urls  = (array) $request->input('social_url', []);

        $allowedIcons = array_keys(WebsiteContent::socialIconOptions());
        $socialLinks  = [];

        foreach ($urls as $i => $url) {
            $url  = trim((string) $url);
            $icon = trim((string) ($icons[$i] ?? ''));

            // Baris tanpa URL dianggap kosong & dilewati
            if ($url === '' || $url === '#') {
                continue;
            }
            if (! in_array($icon, $allowedIcons, true)) {
                $icon = 'fas fa-globe';
            }

            $socialLinks[] = ['icon' => $icon, 'url' => $url];
        }

        Setting::set('web_social_links', json_encode($socialLinks, JSON_UNESCAPED_SLASHES));

        // ── Gambar background hero (multi-upload → slider otomatis di depan) ──
        // Daftar gambar saat ini (bisa berasal dari JSON baru atau fallback legacy)
        $heroPaths = WebsiteContent::heroImagePaths();

        // 1) Hapus gambar yang dicentang "hapus"
        $hapus = array_filter((array) $request->input('hapus_gambar', []));
        foreach ($hapus as $path) {
            $path = trim((string) $path);
            if (in_array($path, $heroPaths, true)) {
                Storage::disk('public')->delete($path);
                $heroPaths = array_values(array_diff($heroPaths, [$path]));
            }
        }

        // 2) Tambahkan gambar baru yang diupload (jumlah tidak dibatasi)
        if ($request->hasFile('web_hero_image')) {
            foreach ($request->file('web_hero_image') as $file) {
                if ($file?->isValid()) {
                    $heroPaths[] = $file->store('website', 'public');
                }
            }
        }

        // 3) Simpan daftar sebagai JSON + mirror ke key legacy (gambar pertama)
        Setting::set('web_hero_images', json_encode(array_values($heroPaths), JSON_UNESCAPED_SLASHES));
        Setting::set('web_hero_image', $heroPaths[0] ?? '');

        // ── Foto galeri kegiatan (multi-upload, tampil di halaman depan) ──
        $galeriPaths = WebsiteContent::galeriPhotoPaths();

        // 1) Hapus foto yang dicentang "hapus"
        foreach (array_filter((array) $request->input('hapus_galeri', [])) as $path) {
            $path = trim((string) $path);
            if (in_array($path, $galeriPaths, true)) {
                Storage::disk('public')->delete($path);
                $galeriPaths = array_values(array_diff($galeriPaths, [$path]));
            }
        }

        // 2) Tambahkan foto baru (jumlah tidak dibatasi)
        if ($request->hasFile('galeri_foto')) {
            foreach ($request->file('galeri_foto') as $file) {
                if ($file?->isValid()) {
                    $galeriPaths[] = $file->store('website/galeri', 'public');
                }
            }
        }

        Setting::set('web_galeri_fotos', json_encode(array_values($galeriPaths), JSON_UNESCAPED_SLASHES));

        return redirect()->route('admin.website.index')
            ->with('success', 'Konten website berhasil diperbarui.');
    }
}
