<?php

use App\Routes\ApiRouter;
use App\Routes\WebRouter;
use App\Utilities\CommonHelper;

require_once(__DIR__ . '/../vendor/autoload.php');

// 環境変数読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// ヘルパ読み込み
require_once(__DIR__ . '/../app/utilities/CommonHelper.php');

// ロガー読み込み
require_once(__DIR__ . '/../app/utilities/Logger.php');

// メーラ読み込み
require_once(__DIR__ . '/../app/utilities/Mailer.php');

// コントローラ読み込み
$controllerDir = __DIR__ . '/../app/controllers/';
require_once("{$controllerDir}Controller.php");
foreach (CommonHelper::getFilesRecursive($controllerDir) as $controllerFile) {
    require_once($controllerFile);
}

// モデル読み込み
$modelDir = __DIR__ . '/../app/models/';
require_once("{$modelDir}Model.php");
foreach (CommonHelper::getFilesRecursive($modelDir) as $modelFile) {
    require_once($modelFile);
}

// ルーター読み込み
$routerDir = __DIR__ . '/../app/routes/';
require_once("{$routerDir}Router.php");
foreach (CommonHelper::getFilesRecursive($routerDir) as $routerFile) {
    require_once($routerFile);
}

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_save_path(__DIR__ . '/../storage/sessions');
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 10);
    ini_set('session.gc_maxlifetime', 2 * 60 * 60);
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);

    session_start();

    if (!isset($_SESSION['csrf_token'])) {
        // CSRFトークン生成
        $tokeByte = openssl_random_pseudo_bytes(16);
        $csrfToken = bin2hex($tokeByte);
        $_SESSION['csrf_token'] = $csrfToken;
    }
}

// ルーティング
$method = $_SERVER['REQUEST_METHOD'];
$routes = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
$csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

if (isset($routes[0]) && $routes[0] === 'api') {
    array_shift($routes);
    $apiRouter = ApiRouter::getInstance();
    $apiRouter->route($method, $routes, $_REQUEST, $csrfToken);
} else {
    $webRouter = WebRouter::getInstance();
    $webRouter->route($method, $routes, $_REQUEST, $csrfToken);
}
