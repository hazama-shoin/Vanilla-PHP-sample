<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\User;
use App\Utilities\CommonHelper;
use App\Utilities\Mailer;

class MailController extends Controller
{
    private Mailer $mailer;

    public function __construct()
    {
        parent::__construct();

        // Mailer初期化
        $this->mailer = Mailer::getInstance();
    }

    /**
     * 会員登録メール送信処理
     *
     * @param array $request
     */
    public function sendRegisterMail(array $request): void
    {
        if (!isset($request['name']) && !isset($request['email'])) {
            $this->apiResponse(400, '会員登録メールの送信に失敗しました。');
            return;
        }
        $name = $request['name'];
        $email = $request['email'];

        // メールアドレスが登録済みならメール送信しない
        $userModel = new User();
        $searchParam = [
            'and_where' => [
                'email' => ['keyword' => $email],
            ],
        ];
        $user = $userModel->search($searchParam, 1, 1);
        if (is_array($user) && count($user) > 0) {
            $this->apiResponse(400, '既に登録済みのメールアドレスです。別のメールアドレスをお試しください。');
            return;
        }

        $tokenParam = ['name' => $name, 'email' => $email];
        $token = CommonHelper::encodeHexString($tokenParam);

        $subject = '【会員管理システム】会員登録のご案内';
        $from = $_ENV['MAIL_FROM_ADDRESS'] ?? null;

        $this->smarty->assign('name', $name);
        $this->smarty->assign(
            'sitePrefix',
            ($_ENV['APP_SSL_ENABLED'] === 'true') ? 'https' : 'http'
        );
        $this->smarty->assign('siteHost', $_ENV['APP_HOST'] ?? 'localhost:8080');
        $this->smarty->assign('token', $token);
        $body = $this->smarty->fetch('mails/register_mail.tpl');

        $statusCode = 200;
        $message = '会員登録メールを送信しました。メールに記載したリンクから本登録を行ってください。';
        if (!$this->mailer->sendMail($email, $subject, $body, $from)) {
            $statusCode = 401;
            $message = '会員登録メールの送信に失敗しました。';
        }

        $this->apiResponse($statusCode, $message);
    }
}
