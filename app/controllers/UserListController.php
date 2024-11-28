<?php

namespace App\Controllers;

use App\Models\User;

class UserListController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * 会員一覧画面を表示
     *
     * @param int $page
     */
    public function index(int $page = 1): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            return;
        }

        $members = $this->userModel->get(null, $page);
        $maxPages = isset($members[0]) ? (int) ($members[0]['total_count'] / 10) + 1 : 1;
        $this->smarty->assign('user', $_SESSION['user']);
        $this->smarty->assign('members', $members);
        $this->smarty->assign('currentPage', $page);
        $this->smarty->assign('maxPages', $maxPages);
        $this->smarty->display('user_list.tpl');
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
        if (!$this->userModel->delete($id)) {
            $statusCode = 400;
            $message = "会員ID: {$id} の削除に成功しました。";
        }
        $this->apiResponse($statusCode, $message);
    }
}
