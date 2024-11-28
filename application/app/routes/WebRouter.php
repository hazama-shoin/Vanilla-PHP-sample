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
class WebRouter extends Router
{
    /**
     * GETメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function get(array $routes, array $request, bool $isAuthenticated = false): void
    {
        $route = $routes[0] ?? '';
        $id = (isset($routes[1]) && is_numeric($routes[1])) ? $routes[1] : null;

        if ($isAuthenticated) {
            switch ($route) {
                case 'register':
                    // 登録画面表示
                    $registerController = new RegisterController();
                    $registerController->index($request);
                    break;
                case 'profile':
                    if (isset($id)) {
                        // プロフィール画面を表示
                        $profileController = new ProfileController();
                        $profileController->index($id);
                    } else {
                        $this->redirect();
                    }
                    break;
                case 'edit-user':
                    if (isset($id)) {
                        // 会員更新画面を表示
                        $editUserController = new EditUserController();
                        $editUserController->index($id);
                    } else {
                        $this->redirect();
                    }
                    break;
                case 'edit-profile':
                    if (isset($id)) {
                        // プロフィール更新画面を表示
                        $editUserController = new EditUserController();
                        $editUserController->index($id, true);
                    } else {
                        $this->redirect();
                    }
                    break;
                case '':
                case 'pages':
                    $userListController = new UserListController();
                    $userListController->index($id ?? 1, $request);
                    break;
                default:
                    $this->redirect();
                    break;
            }
        } else {
            switch ($route) {
                case 'register':
                    // 会員登録画面を表示
                    $registerController = new RegisterController();
                    $registerController->index($request);
                    break;
                case '':
                case 'login':
                    // ログイン画面表示
                    $loginController = new LoginController();
                    $loginController->index();
                    break;
                default:
                    $this->redirect();
                    break;
            }
        }
    }

    /**
     * POSTメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function post(array $routes, array $request, bool $isAuthenticated = false): void
    {
        $this->redirect();
    }

    /**
     * リダイレクト
     *
     * @param string $redierctUrl リダイレクト先URL
     */
    private function redirect(string $redirectUrl = '/'): void
    {
        header("Location: {$redirectUrl}");
    }
}
