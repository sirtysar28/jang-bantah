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

        return view('admin.website.index', compact('content'));
    }

    /**
     * Simpan seluruh konten website.
     * Field yang dikosongkan otomatis kembali ke nilai default.
     */
    public function update(Request $request)
    {
        $defaults = WebsiteContent::defaults();

        // web_hero_image ditangani terpisah sebagai upload file
        $fields = array_values(array_diff(array_keys($defaults), ['web_hero_image']));

        $rules = collect($fields)->mapWithKeys(fn ($f) => [$f => 'nullable|string|max:3000'])->all();
        $rules['web_hero_type'] = 'nullable|in:video,image';
        $rules['web_hero_image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'; // maks 5MB
        $rules['hapus_gambar'] = 'nullable|boolean';

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

        // ── Upload / hapus gambar background hero ──
        if ($request->hasFile('web_hero_image')) {
            // Hapus gambar lama bila ada
            if ($old = WebsiteContent::heroImagePath()) {
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('web_hero_image')->store('website', 'public');
            Setting::set('web_hero_image', $path);
        } elseif ($request->boolean('hapus_gambar')) {
            if ($old = WebsiteContent::heroImagePath()) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('web_hero_image', '');
        }

        return redirect()->route('admin.website.index')
            ->with('success', 'Konten website berhasil diperbarui.');
    }
}
