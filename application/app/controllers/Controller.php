<?php

namespace App\Controllers;

use App\Utilities\CommonHelper;
use App\Utilities\Logger;
use Smarty\Smarty;

class Controller
{
    protected Logger $logger;
    protected Smarty $smarty;

    public function __construct()
    {
        // ロガー初期化
        $this->logger = Logger::getInstance();

        // Smarty初期化
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir('../views');
        $this->smarty->setCompileDir('../storage/views');
    }

    /**
     * APIレスポンス
     *
     * @param int $statusCode ステータスコード
     * @param string $message メッセージ
     */
    protected function apiResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        echo json_encode(['status_code' => $statusCode, 'message' => $message]);
    }

    /**
     * View呼び出し
     *
     * @param string $templateName テンプレート名。拡張子は省略
     * @param array $attributes [['Smarty変数名' => 渡す値], ['Smarty変数名' => 渡す値], ...]
     */
    protected function view(string $templateName, array $attributes = []): void
    {
        $csrfToken = $_SESSION['csrf_token'] ?? '';

        $this->smarty->assign('csrfToken', $csrfToken);
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $avatarSrc = $this->avatarSrc($user['avatar_id'] ?? '');
            $this->smarty->assign('user', $user);
            $this->smarty->assign('userAvatarSrc', $avatarSrc);
        }
        foreach ($attributes as $key => $parameter) {
            $this->smarty->assign($key, $parameter);
        }
        $this->smarty->display("{$templateName}.tpl");
    }

    /**
     * アバターIDに対応する画像文字列を返却
     *
     * @param string $avatarId
     * @return string
     */
    protected function avatarSrc(string $avatarId, string $defalutImageSrc = '/images/noimage_150x150.jpg'): string
    {
        return CommonHelper::getImageToBase64($avatarId) ?? $defalutImageSrc;
    }
}
