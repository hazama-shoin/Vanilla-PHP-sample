<?php

namespace App\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * プロフィール画面を表示
     *
     * @param int $id
     */
    public function index(int $id): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            return;
        }

        $member = $this->userModel->get($id);
        if (!is_array($member) || empty($member)) {
            header('Location: /');
            return;
        }
        $this->smarty->assign('user', $_SESSION['user']);
        $this->smarty->assign('member', $member[0]);
        $this->smarty->display('profile.tpl');
    }
}
