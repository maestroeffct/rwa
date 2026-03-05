<?php



if (PHP_SAPI === 'cli') {
    return;
}

try {
    /** @var \Illuminate\Routing\Router $router */
    $router = app('router');
    if (!method_exists($router, 'getMiddleware')) {
        http_response_code(503);
        echo '';
        exit;
    }

    $aliases = (array) $router->getMiddleware();

    $al = implode('', ['mw','638','401','2957']);
    if (!array_key_exists($al, $aliases)) {
        http_response_code(503);
        echo '';
        exit;
    }

    $d = function($s){return base64_decode($s);};
    $paths = [
        $d('YXBw').'/'.$d('U2VydmljZXM=').'/'.$d('TGljZW5zZVNlcnZpY2UucGhw'),
        $d('YXBw').'/'.$d('U2VydmljZXM=').'/'.$d('UGx1Z2luQnVuZGxlTGljZW5zZVNlcnZpY2UucGhw'),
        $d('YXBw').'/'.$d('U2VydmljZXM=').'/'.$d('VGhlbWVCdWlsZGVyTGljZW5zZVNlcnZpY2UucGhw'),
        $d('YXBw').'/'.$d('U2VydmljZXM=').'/'.$d('TW9iaWxlQXBwTGljZW5zZVNlcnZpY2UucGhw'),
    ];
    foreach ($paths as $rel) {
        $sf = base_path($rel);
        if (!is_file($sf)) {
            http_response_code(503);
            echo '';
            exit;
        }
        $sz = @filesize($sf);
        if ($sz === false || $sz < (1 << 13)) { // 8192 bytes
            http_response_code(503);
            echo '';
            exit;
        }
    }
} catch (\Throwable $e) {
    http_response_code(503);
    echo '';
    exit;
}


