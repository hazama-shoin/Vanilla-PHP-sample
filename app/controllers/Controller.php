<?php

namespace App\Controllers;

use Smarty\Smarty;

class Controller
{
    protected Smarty $smarty;

    public function __construct()
    {
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
}
