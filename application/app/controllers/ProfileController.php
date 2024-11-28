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
        $member = $this->userModel->get($id);
        if (!is_array($member) || empty($member)) {
            header('Location: /');
            return;
        }
        $this->view(
            'profile',
            [
                'member' => $member[0],
                'avatarSrc' => $this->avatarSrc($member[0]['avatar_id'] ?? ''),
            ]
        );
    }
}
