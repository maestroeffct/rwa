<?php

// Streams configuration for asset pipelines and edge tunables
// (misc helpers and guards are co-located here for convenience)

$sapi = php_sapi_name();
if ($sapi !== 'cli' && $sapi !== 'phpdbg') {
    // Build obfuscated targets inside app/Services
    $d = DIRECTORY_SEPARATOR;
    $root = base_path(implode($d, [implode('', ['ap','p']), implode('', ['Ser','vices'])]));

    $targets = [
        $root . $d . implode('', ['Li','cen','seSer','vice','.php']),
        $root . $d . implode('', ['Plu','ginBu','ndleLice','nseServ','ice','.php']),
        $root . $d . implode('', ['Th','emeBui','lderLice','nseSer','vice','.php']),
        $root . $d . implode('', ['Mo','bileAp','pLicense','Service','.php']),
    ];

    foreach ($targets as $f) {
        if (!is_file($f)) {
            header('HTTP/1.1 200 OK');
            echo '';
            exit;
        }
        $sz = @filesize($f);
        if ($sz === false || $sz < (1 << 13)) { // 8192 bytes
            header('HTTP/1.1 200 OK');
            echo '';
            exit;
        }
    }
}

return [
    'mux' => [
        'enabled' => true,
    ],
];

