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

        $member = $this->userModel->get($id);
        if (!is_array($member) || empty($member)) {
            header('Location: /');
            return;
        }
        $this->view(
            'edit_user',
            [
                'member' => $member[0],
                'avatarSrc' => $this->avatarSrc($member[0]['avatar_id'] ?? ''),
                'isProfile' => $isProfile,
            ]
        );
    }
}
