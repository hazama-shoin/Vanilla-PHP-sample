<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * ログイン処理
     *
     * @param array $request
     */
    public function login(array $request): void
    {
        if (!isset($request['email']) || !isset($request['password'])) {
            $this->apiResponse(400, 'ログイン処理に失敗しました。');
            return;
        }

        $userModel = new User();
        $user = $userModel->authenticate($request['email'], $request['password']);

        $statusCode = 200;
        $message = 'ログイン処理に成功しました。';
        if (is_array($user)) {
            $_SESSION['user'] = $user;
        } else {
            $statusCode = 401;
            $message = 'ログイン処理に失敗しました。';
        }

        $this->apiResponse($statusCode, $message);
    }

    /**
     * ログアウト処理
     */
    public function logout(): void
    {
        // セッション破棄
        $_SESSION = [];
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 42000, '/');
        }
        session_destroy();

        $statusCode = 200;
        $message = 'ログアウト処理に成功しました。';
        if (isset($_SESSION['user'])) {
            $statusCode = 500;
            $message = 'ログアウト処理に失敗しました。';
        }

        $this->apiResponse($statusCode, $message);
    }
}
