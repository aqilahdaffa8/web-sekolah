<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(): JsonResponse
    {
        return response()->json(SiteSetting::all()->groupBy('group'));
    }

    public function upsert(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable', 'string'],
            'settings.*.group' => ['nullable', 'string'],
        ]);

        foreach ($data['settings'] as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'] ?? null, 'group' => $setting['group'] ?? 'general']
            );
        }

        $this->logger->log($request->user()->id, 'upserted', 'site_settings');

        return response()->json(['message' => 'Settings saved.', 'settings' => SiteSetting::all()]);
    }
}
