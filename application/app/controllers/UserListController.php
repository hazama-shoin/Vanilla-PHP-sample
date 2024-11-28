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
     * @param array $request
     */
    public function index(int $page, array $request): void
    {
        $andWhere = [];
        if (!$_SESSION['user']['is_admin']) {
            $andWhere['id'] = [
                'keyword' => $_SESSION['user']['id'],
                'is_partial' => false,
            ];
        }

        $searchParam = [];
        $searchWord = '';
        if (isset($request['search'])) {
            $searchWord = $request['search'];
            $names = preg_split('/[\p{Z}\p{Cc}]++/u', $searchWord, -1, PREG_SPLIT_NO_EMPTY);
            $andWhere['name'] = [
                'keyword' => $names,
                'is_partial' => true,
            ];
        }
        if (!empty($andWhere)) {
            $searchParam['and_where'] = $andWhere;
        }
        $members = $this->userModel->search($searchParam, $page);
        foreach ($members as &$member) {
            $member['avatar_src'] = $this->avatarSrc($member['avatar_id'] ?? '');
        }
        $maxPages = isset($members[0]) ? (int) ceil($members[0]['total_count'] / 10) : 1;

        $this->view(
            'user_list',
            [
                'members' => $members,
                'currentPage' => $page,
                'maxPages' => $maxPages,
                'searchWord' => $searchWord,
            ]
        );
    }
}
