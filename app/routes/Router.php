<?php

namespace App\Routes;

use App\Controllers\EditUserController;
use App\Controllers\LoginController;
use App\Controllers\ProfileController;
use App\Controllers\RegisterController;
use App\Controllers\UserListController;

/**
 * ルーティング
 */
class Router
{
    /**
     * コンストラクタ
     */
    private function __construct()
    {
        //
    }

    /**
     * GETメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthentication
     */
    public static function get(string $route, array $request, bool $isAuthentication = false): void
    {
        $id = $request['id'] ?? null;
        if ($isAuthentication) {
            switch ($route) {
                case 'register':
                    // 登録画面表示
                    $registerController = new RegisterController();
                    $registerController->index();
                    break;
                case 'profile':
                    // プロフィール画面を表示
                    $profileController = new ProfileController();
                    $profileController->index($id);
                    break;
                case 'edit-user':
                    if (isset($id)) {
                        // 会員更新画面を表示
                        $editUserController = new EditUserController();
                        $editUserController->index($id);
                    } else {
                        header('Location: /');
                    }
                    break;
                case 'edit-profile':
                    if (isset($id)) {
                        // プロフィール更新画面を表示
                        $editUserController = new EditUserController();
                        $editUserController->index($id, true);
                    } else {
                        header('Location: /');
                    }
                    break;
                case 'delete-user':
                    if (isset($id)) {
                        // 会員削除処理実行
                        $userListController = new UserListController();
                        $userListController->delete($id);
                    } else {
                        header('Location: /');
                    }
                    break;
                case 'logout':
                    // ログアウト処理実行
                    $loginController = new LoginController();
                    $loginController->logout();
                    break;
                case '':
                case 'pages':
                    $userListController = new UserListController();
                    $userListController->index($id ?? 1);
                    break;
                default:
                    header('Location: /');
                    break;
            }
        } else {
            switch ($route) {
                case 'register':
                    // 会員登録画面を表示
                    $registerController = new RegisterController();
                    $registerController->index();
                    break;
                case '':
                case 'login':
                    // ログイン画面表示
                    $loginController = new LoginController();
                    $loginController->index();
                    break;
                default:
                    header('Location: /');
                    break;
            }
        }
    }

    /**
     * POSTメソッド
     *
     * @param string $route
     * @param bool $isAuthentication
     */
    public static function post(string $route, array $request, bool $isAuthentication = false): void
    {
        // CSRFトークン検証
        if (
            !isset($_SESSION['csrf_token'])
            || !isset($request['csrf_token'])
            || $_SESSION['csrf_token'] !== $request['csrf_token']
        ) {
            http_response_code(500);
            echo json_encode(['status_code' => 500, 'message' => '何らかのエラーが発生しました。']);
            return;
        }

        $id = $request['id'] ?? null;
        if ($isAuthentication) {
            switch ($route) {
                case 'register':
                    // 登録処理実行
                    $registerController = new RegisterController();
                    $registerController->register($request);
                    break;
                case 'update-user':
                    if (isset($id)) {
                        // 会員更新処理実行
                        $editUserController = new EditUserController();
                        $editUserController->update($id, $request);
                    } else {
                        header('Location: /');
                    }
                    break;
                case 'delete-user':
                    if (isset($id)) {
                        // 会員削除処理実行
                        $userListController = new UserListController();
                        $userListController->delete($id);
                    } else {
                        header('Location: /');
                    }
                    break;
                default:
                    header('Location: /');
                    break;
            }
        } else {
            switch ($route) {
                case 'register':
                    // 登録処理実行
                    $registerController = new RegisterController();
                    $registerController->register($request);
                    break;
                case 'login':
                    // ログイン処理実行
                    $loginController = new LoginController();
                    $loginController->login($request);
                    break;
                default:
                    header('Location: /');
                    break;
            }
        }
    }
}
