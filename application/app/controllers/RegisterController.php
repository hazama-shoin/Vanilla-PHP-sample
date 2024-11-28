<?php

namespace App\Controllers;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * 会員登録画面を表示
     *
     * @param array $request
     */
    public function index(array $request): void
    {
        $parameters = [];
        if (isset($request['token']) && is_string($request['token'])) {
            $key = $_ENV['APP_TOKEN_ENCRYPT_KEY'] ?? '';
            $iv = $_ENV['APP_TOKEN_ENCRYPT_IV'] ?? '';
            $json = openssl_decrypt(hex2bin($request['token']), 'AES-256-CBC', $key, 0, $iv);
            $parameters = json_decode($json, true) ?? [];

            // 既に会員登録済みかチェック
            if (isset($parameters['email'])) {
                $email = $parameters['email'];
                $userModel = new User();
                $searchParam = [
                    'and_where' => [
                        'email' => ['keyword' => $email],
                    ],
                ];
                $user = $userModel->search($searchParam, 1, 1);
                if (isset($user[0]['email']) && $user[0]['email'] === $email) {
                    // 会員登録済みならログイン画面にリダイレクト
                    header('Location: /');
                    return;
                }
            }
        }
        if (!isset($parameters['name']) || !isset($parameters['email'])) {
            $parameters = [];
        }

        $this->view('register', ['parameters' => $parameters]);
    }
}
