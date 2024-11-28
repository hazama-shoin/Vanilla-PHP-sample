<?php

namespace App\Routes;

use App\Controllers\Api\LoginController;
use App\Controllers\Api\UserController;
use App\Controllers\Api\MailController;

/**
 * ルーティング
 */
class ApiRouter extends Router
{
    /**
     * POSTメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function post(array $routes, array $request, bool $isAuthenticated = false): void
    {
        if (!isset($routes[0])) {
            $this->response(400);
            return;
        }

        $route = $routes[0];

        if ($isAuthenticated) {
            switch ($route) {
                case 'user':
                    // 登録処理実行
                    $userController = new UserController();
                    $userController->store($request);
                    break;
                case 'logout':
                    // ログアウト処理実行
                    $loginController = new LoginController();
                    $loginController->logout();
                    break;
                default:
                    $this->response(404);
                    break;
            }
        } else {
            switch ($route) {
                case 'send_register_mail':
                    // 会員登録メール送信処理実行
                    $mailController = new MailController();
                    $mailController->sendRegisterMail($request);
                    break;
                case 'user':
                    // 登録処理実行
                    $userController = new UserController();
                    $userController->store($request);
                    break;
                case 'login':
                    // ログイン処理実行
                    $loginController = new LoginController();
                    $loginController->login($request);
                    break;
                default:
                    $this->response(404);
                    break;
            }
        }
    }

    /**
     * PUTメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function put(array $routes, array $request, bool $isAuthenticated = false): void
    {
        if (
            !isset($routes[0])
            || !isset($routes[1])
            || !is_numeric($routes[1])
        ) {
            $this->response(400);
            return;
        }

        $route = $routes[0];
        $id = $routes[1];

        if ($isAuthenticated) {
            switch ($route) {
                case 'user':
                    // 会員更新処理実行
                    $editUserController = new UserController();
                    $editUserController->update($id, $request);
                    break;
                default:
                    $this->response(404);
                    break;
            }
        } else {
            $this->response(401);
        }
    }

    /**
     * DELETEメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function delete(array $routes, array $request, bool $isAuthenticated = false): void
    {
        if (
            !isset($routes[0])
            || !isset($routes[1])
            || !is_numeric($routes[1])
        ) {
            $this->response(400);
            return;
        }

        $route = $routes[0];
        $id = $routes[1];

        if ($isAuthenticated) {
            switch ($route) {
                case 'user':
                    if ($_SESSION['user']['is_admin']) {
                        // 会員削除処理実行
                        $userController = new UserController();
                        $userController->delete($id);
                    } else {
                        $this->response(404);
                    }
                    break;
                default:
                    $this->response(404);
                    break;
            }
        } else {
            $this->response(401);
        }
    }
}
