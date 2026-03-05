<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseCode;
use App\Services\MobileAppLicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MobileAppLicenseController extends Controller
{
    protected $licenseService;

    public function __construct(MobileAppLicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Show the Mobile App purchase code entry form
     */
    public function show()
    {
        $purchaseCode = PurchaseCode::getMobileAppPurchaseCode();
        $pageTitle = 'Enter Mobile App License';
        
        // Check if purchase code is empty and show error message
        if (empty($purchaseCode) && !session()->has('mobile_app_error')) {
            session()->flash('mobile_app_error', 'No purchase code provided for Mobile App. Please enter a valid purchase code.');
        }
        
        return view('admin.licenses.mobile_app_enter', compact('purchaseCode', 'pageTitle'));
    }

    /**
     * Store and validate Mobile App purchase code
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string|size:36'
        ]);

        $purchaseCode = $request->input('purchase_code');
        
        // Validate the purchase code with forceCheck=true to bypass cache
        $validationResult = $this->licenseService->func3847291650($purchaseCode, true);
        
        // Debug logging
        Log::debug('Mobile App purchase code validation result', [
            'code' => substr($purchaseCode, 0, 4) . '****', // Mask the code for security
            'valid' => $validationResult['valid'] ?? false,
            'error' => $validationResult['error'] ?? null,
            'message' => $validationResult['message'] ?? null
        ]);
        
        if (!$validationResult['valid']) {
            $errorType = $validationResult['error'] ?? MobileAppLicenseService::ERROR_INVALID_CODE;
            $errorMessage = $validationResult['message'] ?? 'Invalid purchase code';
            
            // Customize the error message based on error type
            if ($errorType === MobileAppLicenseService::ERROR_DOMAIN_MISMATCH && isset($validationResult['registered_domain'])) {
                session()->flash('mobile_app_registered_domain', $validationResult['registered_domain']);
            }
            
            return back()->withErrors(['purchase_code' => $errorMessage])->withInput();
        }
        
        // Check if the license type is compatible with main license
        $licenseType = $validationResult['license_type'] ?? 'Regular license';
        $compatibilityResult = $this->licenseService->func5629384175($licenseType);
        
        if (!$compatibilityResult['valid']) {
            return back()->withErrors(['purchase_code' => $compatibilityResult['message']])->withInput();
        }
        
        // Save the valid purchase code
        PurchaseCode::updateMobileAppPurchaseCode($purchaseCode, $licenseType);
        
        return redirect()->route('admin.licenses')
            ->with('success', 'Mobile App license validated and saved successfully.');
    }
} 