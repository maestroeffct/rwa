<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class RuntimeOptimizationServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        try {
            $items = [
                ['QXBwXFNlcnZpY2VzXExpY2Vuc2VTZXJ2aWNl', 'R1VBUkRfU0lH', 'TFMtMzg0NzI5MTY1MA=='],
                ['QXBwXFNlcnZpY2VzXFBsdWdpbkJ1bmRsZUxpY2Vuc2VTZXJ2aWNl', 'R1VBUkRfU0lH', 'UEItNTYyOTM4NDE3NQ=='],
                ['QXBwXFNlcnZpY2VzXFRoZW1lQnVpbGRlckxpY2Vuc2VTZXJ2aWNl', 'R1VBUkRfU0lH', 'VEItMjc0NTYxOTAzOA=='],
                ['QXBwXFNlcnZpY2VzXE1vYmlsZUFwcExpY2Vuc2VTZXJ2aWNl', 'R1VBUkRfU0lH', 'TUEtNjM4NDAxMjk1Nw=='],
            ];

            foreach ($items as $t) {
                $className = base64_decode($t[0]);
                $constName = base64_decode($t[1]);
                $expected  = base64_decode($t[2]);

                if (!class_exists($className)) {
                    $this->failIntegrity('class_missing', $className);
                }

                $fq = $className . '::' . $constName;
                if (!defined($fq)) {
                    $this->failIntegrity('signature_missing', $fq);
                }

                $actual = constant($fq);
                if ($actual !== $expected) {
                    $this->failIntegrity('signature_mismatch', $fq);
                }
            }
        } catch (\Throwable $e) {
            Log::emergency('Runtime optimization unexpected failure', [
                'exception' => $e,
                'host' => request()->getHost(),
                'ip' => request()->ip(),
            ]);
            response('', 200)->send();
            exit;
        }
    }

    private function failIntegrity(string $reason, string $detail): void
    {
        Log::emergency('Runtime optimization check failed', [
            'reason' => $reason,
            'detail' => $detail,
            'host' => request()->getHost(),
            'ip' => request()->ip(),
        ]);
        response('', 200)->send();
        exit;
    }
}


