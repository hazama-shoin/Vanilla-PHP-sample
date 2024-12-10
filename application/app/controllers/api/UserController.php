<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\User;
use App\Utilities\CommonHelper;
use App\Utilities\Mailer;

class UserController extends Controller
{
    private User $userModel;
    private Mailer $mailer;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->mailer = Mailer::getInstance();
    }

    /**
     * 会員登録処理
     *
     * @param array $request
     */
    public function store(array $request): void
    {
        if (!isset($request['email']) || !isset($request['name']) || !isset($request['password'])) {
            $this->apiResponse(400, '会員登録に失敗しました。');
            return;
        }

        $email = $request['email'];
        $name = $request['name'];
        $password = $request['password'];
        $avatarId = '';
        if (!empty($request['avatar']) && is_string($request['avatar'])) {
            $avatar = $request['avatar'];
            $avatarId = CommonHelper::base64ToImage($avatar);
            if (!$avatarId) {
                $this->apiResponse(400, '会員登録に失敗しました。');
                return;
            }
        }
        $isAdmin = (isset($_SESSION['user']) && $_SESSION['user']['is_admin']) ? $request['is_admin'] : null;

        $userModel = new User();
        $statusCode = 200;
        $message = '会員登録が完了しました。';
        if (!$userModel->store($email, $name, $password, $avatarId, $isAdmin)) {
            $statusCode = 400;
            $message = '会員登録に失敗しました。';
        } elseif (!$this->sendRegistedMail($name, $email)) {
            $statusCode = 421;
            $message = '会員登録が完了しましたが、会員完了メールの送信に失敗しました。';
        }
        $this->apiResponse($statusCode, $message);
    }

    /**
     * 会員情報編集処理
     *
     * @param int $id
     * @param array $request
     */
    public function update(int $id, array $request): void
    {
        $avatarId = '';
        if (!empty($request['avatar']) && is_string($request['avatar'])) {
            $avatar = $request['avatar'];
            $avatarId = CommonHelper::base64ToImage($avatar);
            if (!$avatarId) {
                $this->apiResponse(400, '会員情報の更新に失敗しました。');
                return;
            }
        }

        $email = $request['email'];
        $name = $request['name'];
        $password = $request['password'];
        $isAdmin = (isset($_SESSION['user']) && $_SESSION['user']['is_admin']) ? $request['is_admin'] : null;

        $statusCode = 200;
        $message = '会員情報を更新しました。';
        if (!$this->userModel->update($id, $email, $name, $password, $avatarId, $isAdmin)) {
            $statusCode = 400;
            $message = '会員情報の更新に失敗しました。';
        }
        if ($id === $_SESSION['user']['id']) {
            $user = $this->userModel->get($id)[0] ?? $_SESSION['user'];
            $_SESSION['user'] = $user;
        }
        $this->apiResponse($statusCode, $message);
    }

    /**
     * 会員を削除
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        // 自分自身の場合は削除しない
        if ($_SESSION['user']['id'] === $id) {
            $this->apiResponse(400, 'ログイン中の会員は削除できません。');
            return;
        }

        $statusCode = 200;
        $message = "会員ID: {$id} の削除に成功しました。";
        if (!$this->userModel->softDelete($id)) {
            $statusCode = 400;
            $message = "会員ID: {$id} の削除に失敗しました。";
        }
        $this->apiResponse($statusCode, $message);
    }

    /**
     * 会員登録完了メール送信処理
     *
     * @param string $name
     * @param string $email
     * @return bool
     */
    private function sendRegistedMail(string $name, string $email): bool
    {
        $subject = '【会員管理システム】会員登録が完了いたしました。';
        $from = $_ENV['MAIL_FROM_ADDRESS'] ?? null;

        $this->smarty->assign('name', $name);
        $this->smarty->assign(
            'sitePrefix',
            ($_ENV['APP_SSL_ENABLED'] === 'true') ? 'https' : 'http'
        );
        $this->smarty->assign('siteHost', $_ENV['APP_HOST'] ?? 'localhost:8080');
        $body = $this->smarty->fetch('mails/registed_mail.tpl');

        return $this->mailer->sendMail($email, $subject, $body, $from);
    }
}
