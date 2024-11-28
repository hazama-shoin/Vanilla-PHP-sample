<?php

require_once(__DIR__ . '/../vendor/autoload.php');

// 環境変数読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// ロガー読み込み
require_once(__DIR__ . '/../utilities/Logger.php');

// コントローラ読み込み
foreach (new GlobIterator(__DIR__ . '/../controllers/*.php') as $controllerFile) {
    require_once($controllerFile);
}

// モデル読み込み
foreach (new GlobIterator(__DIR__ . '/../models/*.php') as $modelFile) {
    require_once($modelFile);
}

// ルーター読み込み
require_once(__DIR__ . '/../routes/Router.php');
use App\Routes\Router;

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();

    if (!isset($_SESSION['csrf_token'])) {
        // CSRFトークン生成
        $tokeByte = openssl_random_pseudo_bytes(16);
        $csrfToken = bin2hex($tokeByte);
        $_SESSION['csrf_token'] = $csrfToken;
    }
}

// ルーティング
$isGetMethod = ($_SERVER["REQUEST_METHOD"] === 'GET');
$request = [];
if (!$isGetMethod) {
    $json = file_get_contents('php://input');
    $request = json_decode($json, true) ?? [];
    $request = array_merge($request, $_POST);
}
$routes = array_values(array_filter(explode("/", $_SERVER['REQUEST_URI'])));
$route = !empty($routes) ? $routes[0] : '';
$id = (isset($routes[1]) && is_numeric($routes[1])) ? $routes[1] : null;
if ($id !== null) {
    $request['id'] = (isset($routes[1]) && is_numeric($routes[1])) ? $routes[1] : null;
}
if (isset($_SESSION['user'])) {
    if ($isGetMethod) {
        Router::get($route, $request, true);
    } else {
        Router::post($route, $request, true);
    }
} else {
    if ($isGetMethod) {
        Router::get($route, $request);
    } else {
        Router::post($route, $request);
    }
}
