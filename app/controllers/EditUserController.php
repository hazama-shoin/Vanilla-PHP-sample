<?php

namespace App\Controllers;

use App\Models\User;

class EditUserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * 会員情報編集画面を表示
     */
    public function index(int $id, bool $isProfile = false): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            return;
        }
        $csrfToken = $_SESSION['csrf_token'] ?? '';

        $member = $this->userModel->get($id);
        if (!is_array($member) || empty($member)) {
            header('Location: /');
            return;
        }
        $this->smarty->assign('csrfToken', $csrfToken);
        $this->smarty->assign('user', $_SESSION['user']);
        $this->smarty->assign('member', $member[0]);
        $this->smarty->assign('isProfile', $isProfile);
        $this->smarty->display('edit_user.tpl');
    }

    /**
     * 会員情報編集処理
     *
     * @param int $id
     * @param array $request
     */
    public function update(int $id, array $request): void
    {
        $email = $request['email'];
        $name = $request['name'];
        $password = $request['password'];

        $statusCode = 200;
        $message = '会員情報を更新しました。';
        if (!$this->userModel->update($id, $email, $name, $password)) {
            $statusCode = 400;
            $message = '会員情報の更新に失敗しました。';
        }
        $this->apiResponse($statusCode, $message);
    }
}
