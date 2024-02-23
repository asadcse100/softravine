<?php

namespace App\Http\Controllers\API;

use App\Models\LicenseActivation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class LicenseActivationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'license_key' => 'required|unique:license_activations,license_key',
        ]);

        $licenseKeyRecord = LicenseActivation::where('license_key', $request->license_key)->first();
        
        if (!is_null($licenseKeyRecord)) {
            return response()->json(['message' => 'License code already added!'], 203);
        }

        $licenseCodeRecord = DB::table('ec_order_product')->where('license_code', $request->license_key)->first();

        if (!empty($licenseCodeRecord->license_code) == !empty($request->license_key)) {
            $activation = LicenseActivation::create([
                'domain_name' => $request->domain_name,
                'domain_url' => $request->domain_url,
                'license_key' => $request->license_key
            ]);

            $licenseActivations = LicenseActivation::get();

            foreach ($licenseActivations as $licenseActivation) {
                $domain_name = $licenseActivation->domain_name;
                $created_at = $licenseActivation->created_at;
                $expaired_at = $licenseActivation->expaired_at;
                $activated_at = $licenseActivation->activated_at;
    
                if (!$activated_at) {
                    // Check if it's the first license (no previous license with the same user and domain)
                    $previousLicense = DB::table('license_activations')
                        ->where('domain_name', $domain_name)
                        ->where('id', '<', $licenseActivation->id)
                        ->first();
    
                    if (!$previousLicense) {
                        // First time activation
                        LicenseActivation::where('id', $licenseActivation->id)
                            ->update(['activated_at' => $created_at]);
                    } else {
                        // Check if the previous license is expired
                        if ($expaired_at <= now()) {
                            LicenseActivation::where('id', $licenseActivation->id)
                                ->update(['activated_at' => now()]);
                        }
                    }
                }
            }

            return response()->json(['message' => 'License activation created successfully', 'data' => $activation], 201);
        } else {
            return response()->json(['message' => 'License code not match!'], 202);
        }
    }
}
