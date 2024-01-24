<?php

namespace App\Http\Controllers\API;

use App\Models\LicenseActivation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LicenseActivationController extends Controller
{
    public function store(Request $request)
    {
        dd($request->toArray());
        $request->validate([
            'license_key' => 'required|unique:license_activations,license_key',
        ]);

        $activation = LicenseActivation::create([
            'domain_name' => $request->domain_name,
            'domain_url' => $request->domain_url,
            'license_key' => $request->license_key
        ]);

        return response()->json(['message' => 'License activation created successfully', 'data' => $activation], 201);
    }
}
