<?php

//header('Access-Control-Allow-Origin:*');
//if (isset($_SERVER['APP_DEBUG']) && $_SERVER['APP_DEBUG']) {
//    print_r($_SERVER['APP_DEBUG']);
//    header('Access-Control-Allow-Origin:' . rtrim($_SERVER['HTTP_REFERER'], '/'));
//}

//header('Access-Control-Allow-Credentials:true');
////header('Access-Control-Allow-Headers:X-Requested-With, Content-Type, withCredentials');
//if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') die();

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
