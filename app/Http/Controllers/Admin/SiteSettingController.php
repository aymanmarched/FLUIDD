<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SiteSocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    private function sanitizeSvg(?string $svg): ?string
    {
        if (!$svg)
            return null;

        // remove scripts + inline events
        $svg = preg_replace('/<\s*script.*?>.*?<\s*\/\s*script\s*>/is', '', $svg);
        $svg = preg_replace('/on\w+\s*=\s*"[^"]*"/i', '', $svg);
        $svg = preg_replace("/on\w+\s*=\s*'[^']*'/i", '', $svg);

          // ✅ force currentColor (remove hardcoded fills/strokes)
    $svg = preg_replace('/\sfill\s*=\s*"[^"]*"/i', '', $svg);
    $svg = preg_replace("/\sfill\s*=\s*'[^']*'/i", '', $svg);
    $svg = preg_replace('/\sstroke\s*=\s*"[^"]*"/i', '', $svg);
    $svg = preg_replace("/\sstroke\s*=\s*'[^']*'/i", '', $svg);

    // ✅ ensure svg uses currentColor
    if (stripos($svg, '<svg') !== false && stripos($svg, 'fill=') === false) {
        $svg = preg_replace('/<svg\b/i', '<svg fill="currentColor"', $svg, 1);
    }
        // basic allowlist
        $allowed = '<svg><path><g><circle><rect><polygon><line><polyline><defs><linearGradient><stop><title>';
        $svg = strip_tags($svg, $allowed);

        return trim($svg);
    }

    public function edit()
    {
        $settings = SiteSetting::first();
        $socials = SiteSocialLink::orderBy('sort_order')->get();

        return view('admin.site_settings.edit', compact('settings', 'socials'));
    }

    // =========================================================
    // 1) GENERAL: company_name + logo
    // =========================================================
    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB
        ]);

        $settings = SiteSetting::firstOrCreate([]);

        if ($request->hasFile('logo')) {
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('site', 'public');
        }

        $settings->update([
            'company_name' => $data['company_name'] ?? $settings->company_name,
            'logo' => $data['logo'] ?? $settings->logo,
        ]);

        return back()->with('success', 'Informations générales mises à jour.');
    }

    // =========================================================
    // 2) ADDRESS + MAP
    // =========================================================
    public function updateAddress(Request $request)
    {
        $data = $request->validate([
            'footer_address_line1' => ['nullable', 'string', 'max:200'],
            'footer_address_line2' => ['nullable', 'string', 'max:200'],
            'footer_city' => ['nullable', 'string', 'max:120'],
            'footer_country' => ['nullable', 'string', 'max:120'],
            'footer_map_embed_url' => ['nullable', 'string', 'max:5000'],
        ]);

        $settings = SiteSetting::firstOrCreate([]);

        $settings->update([
            'footer_address_line1' => $data['footer_address_line1'] ?? $settings->footer_address_line1,
            'footer_address_line2' => $data['footer_address_line2'] ?? $settings->footer_address_line2,
            'footer_city' => $data['footer_city'] ?? $settings->footer_city,
            'footer_country' => $data['footer_country'] ?? $settings->footer_country,
            'footer_map_embed_url' => $data['footer_map_embed_url'] ?? $settings->footer_map_embed_url,
        ]);

        return back()->with('success', 'Adresse & Google Maps mis à jour.');
    }

    // =========================================================
    // 3) CONTACT
    // =========================================================
    public function updateContact(Request $request)
    {
        $data = $request->validate([
            'footer_email' => ['nullable', 'email', 'max:190'],
            'footer_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $settings = SiteSetting::firstOrCreate([]);

        $settings->update([
            'footer_email' => $data['footer_email'] ?? $settings->footer_email,
            'footer_phone' => $data['footer_phone'] ?? $settings->footer_phone,
        ]);

        return back()->with('success', 'Contact mis à jour.');
    }

    // =========================================================
    // 4) SOCIALS (create/update/delete by "id")
    // =========================================================
    public function updateSocials(Request $request)
    {
        $validated = $request->validate([
            'socials' => ['nullable', 'array'],

            'socials.*.id' => ['nullable', 'integer'],
            'socials.*.name' => ['nullable', 'string', 'max:50'],
            'socials.*.url' => ['nullable', 'string', 'max:500'],
            'socials.*.color' => ['nullable', 'string', 'max:255'],
            'socials.*.icon_svg' => ['nullable', 'string', 'max:20000'],
            'socials.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'socials.*.is_enabled' => ['nullable'], // checkbox
        ]);

        $settings = SiteSetting::firstOrCreate([]);
        $rows = $request->input('socials', []);

        DB::transaction(function () use ($rows, $settings) {

            $keepIds = [];

            foreach ($rows as $i => $s) {
                $name = trim((string) ($s['name'] ?? ''));
                $url = trim((string) ($s['url'] ?? ''));

                // إذا خاويين بجوج => تجاهلو (وإلا كان قديم غادي يتحيد من db لأنه ما داخلش ف keepIds)
                if ($name === '' && $url === '') {
                    continue;
                }

                // إذا واحد فيهم خاوي => خليه fail منطقي (أحسن من record ناقص)
                if ($name === '' || $url === '') {
                    // نخليها “continue” باش ماينسفطش، ولكن إلا بغيتي strict قولي ونبدلها ل validation stricte
                    continue;
                }

                $payload = [
                    'site_setting_id' => $settings->id,
                    'name' => $name,
                    'url' => $url,
                    'color' => $s['color'] ?? null,
                    'icon_svg' => $this->sanitizeSvg($s['icon_svg'] ?? null),
                    'sort_order' => (int) ($s['sort_order'] ?? $i),
                    'is_enabled' => ((int) ($s['is_enabled'] ?? 0) === 1),
                ];

                $id = $s['id'] ?? null;

                if ($id) {
                    $model = SiteSocialLink::where('id', $id)->first();
                    if ($model) {
                        $model->update($payload);
                        $keepIds[] = $model->id;
                        continue;
                    }
                }

                $model = SiteSocialLink::create($payload);
                $keepIds[] = $model->id;
            }

            // حذف اللي تحيدو من الفورم
            SiteSocialLink::whereNotIn('id', $keepIds)->delete();
        });

        return back()->with('success', 'Réseaux sociaux mis à jour.');
    }


    public function storeSocial(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'url' => ['required', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:255'],
            'icon_svg' => ['nullable', 'string', 'max:20000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_enabled' => ['nullable'], // checkbox
        ]);

        $settings = SiteSetting::firstOrCreate([]);

        SiteSocialLink::create([
            'site_setting_id' => $settings->id,
            'name' => $data['name'],
            'url' => $data['url'],
            'color' => $data['color'] ?? null,
            'icon_svg' => $this->sanitizeSvg($data['icon_svg'] ?? null),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_enabled' => !empty($data['is_enabled']),
        ]);

        return back()->with('success', 'Réseau social ajouté.');
    }

    public function updateSocial(Request $request, SiteSocialLink $social)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'url' => ['required', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:255'],
            'icon_svg' => ['nullable', 'string', 'max:20000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_enabled' => ['nullable'], // checkbox
        ]);

        $social->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'color' => $data['color'] ?? null,
            'icon_svg' => $this->sanitizeSvg($data['icon_svg'] ?? null),
            'sort_order' => (int) ($data['sort_order'] ?? $social->sort_order),
            'is_enabled' => !empty($data['is_enabled']),
        ]);

        return back()->with('success', 'Réseau social mis à jour.');
    }

    public function destroySocial(SiteSocialLink $social)
    {
        $social->delete();
        return back()->with('success', 'Réseau social supprimé.');
    }
}