<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;

class LicenseApiController extends Controller
{
    public function validateLicense(Request $request)
    {
        // Validate request body
        $validated = $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
            'ip' => 'nullable|string',
        ]);

        // Find license key
        $license = License::where('license_key', $validated['license_key'])->first();

        // Check if exists, is active, and is not expired
        if (!$license || $license->status !== 'active' || $license->is_expired) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'License expired or suspended'
            ], 403);
        }

        // Validate domain (case-insensitive hostname/string match)
        $inputDomain = trim(strtolower(parse_url($validated['domain'], PHP_URL_HOST) ?: $validated['domain']));
        $targetDomain = trim(strtolower(parse_url($license->target_domain, PHP_URL_HOST) ?: $license->target_domain));

        if ($inputDomain !== $targetDomain) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'License domain mismatch'
            ], 403);
        }

        // Validate IP if license has target_ip set
        $clientIp = $validated['ip'] ?: $request->ip();
        if ($license->target_ip && $license->target_ip !== $clientIp) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'License IP mismatch'
            ], 403);
        }

        // Update last ping info
        $license->last_ping_ip = $clientIp;
        $license->last_ping_at = now();
        $license->save();

        return response()->json([
            'status' => 'valid',
            'max_routers' => $license->max_routers
        ], 200);
    }
}
