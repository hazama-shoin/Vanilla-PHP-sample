<?php

namespace App\Controllers;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * 会員登録画面を表示
     */
    public function index(): void
    {
        $csrfToken = $_SESSION['csrf_token'] ?? '';
        $isLogined = isset($_SESSION['user']);
        $this->smarty->assign('isLogined', $isLogined);
        if ($isLogined) {
            $this->smarty->assign('user', $_SESSION['user']);
        }
        $this->smarty->assign('csrfToken', $csrfToken);
        $this->smarty->display('register.tpl');
    }

    /**
     * 会員登録処理
     *
     * @param array $request
     */
    public function register(array $request): void
    {
        if (!isset($request['email']) || !isset($request['name']) || !isset($request['password'])) {
            $this->apiResponse(400, '会員登録に失敗しました。');
            return;
        }

        $userModel = new User();
        $statusCode = 200;
        $message = '会員登録が完了しました。';
        if (!$userModel->store($request['email'], $request['name'], $request['password'])) {
            $statusCode = 400;
            $message = '会員登録に失敗しました。';
        };
        $this->apiResponse($statusCode, $message);
    }
}
