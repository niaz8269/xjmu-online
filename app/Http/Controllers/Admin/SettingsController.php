<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\OpsHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** admin/settings : the key/value `site_settings` table shown across the public site. */
class SettingsController extends AdminController
{
    use OpsHelpers;

    private const GROUP_TITLES = [
        'general' => 'General & Hero', 'homepage' => 'Homepage Sections', 'mbbs' => 'MBBS Page',
        'videos' => 'Videos (YouTube)', 'contact' => 'Contact Details', 'social' => 'Social Links',
    ];

    public function __invoke(Request $request)
    {
        $flash = '';
        $flashType = 'success';

        if ($request->isMethod('post')) {
            try {
                $keys = DB::table('site_settings')->pluck('input_type', 'setting_key')->all();
                DB::transaction(function () use ($keys, $request) {
                    foreach ($keys as $key => $type) {
                        $key = (string) $key;
                        if (! array_key_exists($key, $request->post())) {
                            continue;
                        }
                        $val = trim($this->post($request, $key));
                        // Light validation by type; store as-is otherwise.
                        if ($type === 'email' && $val !== '' && ! filter_var($val, FILTER_VALIDATE_EMAIL)) {
                            continue; // skip invalid email rather than store junk
                        }
                        if ($type === 'url' && $val !== '' && ! preg_match('~^https?://~i', $val)) {
                            $val = 'https://' . $val;
                        }
                        DB::table('site_settings')->where('setting_key', $key)->update(['setting_value' => $val]);
                    }
                });
                // App\Support\Settings caches the table for 5 minutes; the original read it live, so drop the cache.
                Cache::forget('site_settings');
                $flash = 'Settings saved.';
            } catch (QueryException $e) {
                Log::error('settings save: ' . $e->getMessage());
                $flash = 'Could not save settings. Please try again.';
                $flashType = 'danger';
            }
        }

        // Load grouped settings for display
        $groups = [];
        try {
            $rows = DB::table('site_settings')->orderBy('group_name')->orderBy('sort_order')->orderBy('label')->get();
            foreach ($this->rows($rows) as $r) {
                $groups[$r['group_name']][] = $r;
            }
        } catch (QueryException $e) {
            Log::error('settings load: ' . $e->getMessage());
        }

        return $this->page('admin.settings', 'Site Settings', 'settings', 'Global content shown across the public website', [
            'flash' => $flash, 'flash_type' => $flashType, 'groups' => $groups, 'groupTitles' => self::GROUP_TITLES,
        ]);
    }
}
