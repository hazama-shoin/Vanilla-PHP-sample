<?php

namespace App\Controllers;

use App\Models\User;

class LoginController extends Controller
{
    /**
     * ログイン画面を表示
     */
    public function index(): void
    {
        $csrfToken = $_SESSION['csrf_token'] ?? '';
        $this->smarty->assign('csrfToken', $csrfToken);
        $this->smarty->display('login.tpl');
    }

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
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        header('Location: /');
    }
}
