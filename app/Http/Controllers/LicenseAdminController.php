<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Services\LicenseGenerator;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LicenseAdminController extends Controller
{
    public function index()
    {
        // Automatically mark active licenses as expired if they passed expires_at
        License::where('status', 'active')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $licenses = License::orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => $licenses->count(),
            'active' => $licenses->where('status', 'active')->count(),
            'suspended' => $licenses->where('status', 'suspended')->count(),
            'expired' => $licenses->where('status', 'expired')->count(),
        ];

        return view('admin.dashboard', compact('licenses', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'target_domain' => 'required|string|max:255',
            'target_ip' => 'nullable|string|max:45',
            'max_routers' => 'nullable|integer|min:1',
            'expires_at' => 'required|date|after_or_equal:today',
        ]);

        $maxRouters = $validated['max_routers'] ?? 5;

        $license = License::create([
            'customer_name' => $validated['customer_name'],
            'license_key' => LicenseGenerator::generate(),
            'target_domain' => $validated['target_domain'],
            'target_ip' => $validated['target_ip'] ?? null,
            'max_routers' => $maxRouters,
            'status' => 'active',
            'expires_at' => Carbon::parse($validated['expires_at']),
        ]);

        return redirect()->route('admin.licenses.index')->with('success', "License created successfully for {$license->customer_name}!");
    }

    public function toggleStatus($id)
    {
        $license = License::findOrFail($id);

        if ($license->status === 'active') {
            $license->status = 'suspended';
        } else {
            if ($license->expires_at->isPast()) {
                $license->expires_at = now()->addDays(30);
            }
            $license->status = 'active';
        }

        $license->save();

        return response()->json([
            'success' => true,
            'status' => $license->status,
            'expires_at' => $license->expires_at->format('Y-m-d H:i:s'),
            'formatted_expires_at' => $license->expires_at->format('M d, Y'),
            'message' => "License status updated to {$license->status}!"
        ]);
    }

    public function extendValidity($id)
    {
        $license = License::findOrFail($id);

        $currentExpiry = $license->expires_at;
        $newExpiry = $currentExpiry->isPast() ? now()->addDays(30) : $currentExpiry->addDays(30);

        $license->expires_at = $newExpiry;

        if ($license->status === 'expired') {
            $license->status = 'active';
        }

        $license->save();

        return response()->json([
            'success' => true,
            'expires_at' => $license->expires_at->format('Y-m-d H:i:s'),
            'formatted_expires_at' => $license->expires_at->format('M d, Y'),
            'status' => $license->status,
            'message' => "Validity extended by 30 days for {$license->customer_name}!"
        ]);
    }
}
