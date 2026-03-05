<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseCode;
use App\Services\ThemeBuilderLicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ThemeBuilderLicenseController extends Controller
{
    protected $licenseService;

    public function __construct(ThemeBuilderLicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Show the theme builder purchase code entry form
     */
    public function show()
    {
        $purchaseCode = PurchaseCode::getThemeBuilderPurchaseCode();
        $pageTitle = 'Enter Theme Builder License';
        
        // Check if purchase code is empty and show error message like plugin page
        if (empty($purchaseCode) && !session()->has('theme_builder_error')) {
            session()->flash('theme_builder_error', 'No purchase code provided for Theme Builder. Please enter a valid purchase code.');
        }
        
        return view('admin.licenses.theme_builder_enter', compact('purchaseCode', 'pageTitle'));
    }

    /**
     * Store and validate theme builder purchase code
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
        Log::debug('Theme Builder purchase code validation result', [
            'code' => substr($purchaseCode, 0, 4) . '****', // Mask the code for security
            'valid' => $validationResult['valid'] ?? false,
            'error' => $validationResult['error'] ?? null,
            'message' => $validationResult['message'] ?? null
        ]);
        
        if (!$validationResult['valid']) {
            $errorType = $validationResult['error'] ?? ThemeBuilderLicenseService::ERROR_INVALID_CODE;
            $errorMessage = $validationResult['message'] ?? 'Invalid purchase code';
            
            // Customize the error message based on error type
            switch ($errorType) {
                case ThemeBuilderLicenseService::ERROR_NO_CODE:
                    $errorMessage = 'This purchase code is not registered.';
                    break;
                
                case ThemeBuilderLicenseService::ERROR_DOMAIN_MISMATCH:
                    $domain = $validationResult['registered_domain'] ?? 'another domain';
                    $errorMessage = "This purchase code is already registered for {$domain}.";
                    break;
                
                case ThemeBuilderLicenseService::ERROR_PRODUCT_MISMATCH:
                    $errorMessage = "Invalid product. This purchase code is for a different product, not for Rocket LMS Theme Builder.";
                    break;
                
                case ThemeBuilderLicenseService::ERROR_INVALID_CODE:
                    $errorMessage = "Invalid purchase code. Please check your code and try again.";
                    break;
                
                case ThemeBuilderLicenseService::ERROR_SERVER_ERROR:
                    $errorMessage = "Server error occurred while validating the license. Please try again later or contact support.";
                    break;
            }
            
            return redirect()->back()
                ->with('theme_builder_error', $errorMessage)
                ->with('theme_builder_error_type', $errorType) // Store error type for the view
                ->withInput();
        }

        // Check if the license type is compatible with main license
        $licenseType = $validationResult['license_type'] ?? 'Regular license';
        $compatibilityResult = $this->licenseService->func5629384175($licenseType);
        
        if (!$compatibilityResult['valid']) {
            return redirect()->back()
                ->with('theme_builder_error', $compatibilityResult['message'])
                ->with('theme_builder_error_type', $compatibilityResult['error'])
                ->withInput();
        }

        // Save purchase code to database
        PurchaseCode::updateThemeBuilderPurchaseCode($purchaseCode, $licenseType);
        
        // Get dynamic admin panel prefix in case it was customized
        $adminPrefix = getAdminPanelUrlPrefix();
        
        // Redirect to licenses list page
        return redirect("/{$adminPrefix}/licenses")
            ->with('success', 'Theme Builder purchase code successfully saved.');
    }
} 