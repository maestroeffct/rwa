<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PurchaseCode;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseCodeController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Show the purchase code entry form
     */
    public function show()
    {
        $purchaseCode = PurchaseCode::getPurchaseCode();
        $licenseType = PurchaseCode::getLicenseType();
        return view('purchase_code.enter', compact('purchaseCode', 'licenseType'));
    }

    /**
     * Store and validate purchase code
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
        Log::debug('Purchase code validation result', [
            'code' => substr($purchaseCode, 0, 4) . '****', // Mask the code for security
            'valid' => $validationResult['valid'] ?? false,
            'error' => $validationResult['error'] ?? null,
            'message' => $validationResult['message'] ?? null,
            'license_type' => $validationResult['license_type'] ?? 'Regular license'
        ]);
        
        if (!$validationResult['valid']) {
            $errorType = $validationResult['error'] ?? LicenseService::ERROR_INVALID_CODE;
            $errorMessage = $validationResult['message'] ?? 'Invalid purchase code';
            
            // Customize the error message based on error type
            switch ($errorType) {
                case LicenseService::ERROR_NO_CODE:
                    $errorMessage = 'This purchase code is not registered.';
                    break;
                
                case LicenseService::ERROR_DOMAIN_MISMATCH:
                    $domain = $validationResult['registered_domain'] ?? 'another domain';
                    $errorMessage = "This purchase code is already registered for {$domain}.";
                    break;
                
                case LicenseService::ERROR_PRODUCT_MISMATCH:
                    $errorMessage = "Invalid product. This purchase code is for a different product.";
                    break;
                
                case LicenseService::ERROR_INVALID_CODE:
                    $errorMessage = "Invalid purchase code. Please check your code and try again.";
                    break;
                
                case LicenseService::ERROR_SERVER_ERROR:
                    $errorMessage = "Server error occurred while validating the license. Please try again later or contact support.";
                    break;
            }
            
            return redirect()->back()
                ->with('purchase_code_error', $errorMessage)
                ->with('error_type', $errorType) // Store error type for the view
                ->withInput();
        }

        // Get license type from validation result
        $licenseType = $validationResult['license_type'] ?? 'Regular license';

        // Save purchase code and license type to database
        PurchaseCode::updatePurchaseCode($purchaseCode, PurchaseCode::TYPE_MAIN, $licenseType);
        
        return redirect('/')
            ->with('success', 'Purchase code successfully saved.');
    }
} 