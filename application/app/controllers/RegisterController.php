<?php

namespace App\Controllers;

use App\Models\User;
use App\Utilities\CommonHelper;

class RegisterController extends Controller
{
    /**
     * 会員登録画面を表示
     *
     * @param array $request
     */
    public function index(array $request): void
    {
        // 未ログインの場合はリクエストパラメータにtokenが必須
        if (!isset($_SESSION['user']) && (!isset($request['token']) || !is_string($request['token']))) {
            // 未ログインでtoken未指定の場合はトップページにリダイレクト
            header('Location: /');
            return;
        }

        $parameters = [];
        if (isset($request['token'])) {
            $parameters = $this->verifyToken($request['token']);
            if (!$parameters) {
                $this->redirect();
                return;
            }
        }

        $this->view('register', ['parameters' => $parameters]);
    }

    /**
     * トークンチェック
     *
     * @param string $token
     * @return array|false トークンが問題なければ復号化した配列を返却。トークンに問題があれば false を返却
     */
    private function verifyToken(string $token): array|false
    {
        $parameters = [];

        if (!is_string($token) || !ctype_xdigit($token) || strlen($token) % 2 !== 0) {
            // tokenは16進数文字列かつ文字数が偶数である必要がある
            return false;
        }

        $parameters = CommonHelper::decodeHexString($token);
        if (!$parameters || empty($parameters)) {
            return false;
        }

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
                // 会員登録済みの場合、そのtokenは利用NG
                return false;
            }
        }

        return $parameters;
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
