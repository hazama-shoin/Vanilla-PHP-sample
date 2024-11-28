<?php

namespace App\Models;

class User extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'users';
    }

    /**
     * ユーザ情報取得
     *
     * @param ?int $id
     * @param int $offset 取得ページ（0から始まる）
     * @param int $limit 取得件数上限
     * @return array|bool 取得に成功した場合、ユーザ情報を返却。取得失敗の場合はfalseを返却
     */
    public function get(?int $id, int $page = 1, int $limit = 10): array|bool
    {
        $andWheres = [];
        if ($id !== null) {
            $andWheres = [
                'id' => ['keyword' => $id],
            ];
        }
        $offset = ($page - 1) * $limit;
        $user = $this->select($andWheres, $offset, $limit);

        // パスワードはレスポンスから除外
        if (is_array($user)) {
            unset($user['password']);
        }

        return $user;
    }

    /**
     * ユーザ検索
     *
     * @param array $attributes 検索条件
     * @param int $offset 取得ページ（0から始まる）
     * @param int $limit 取得件数上限
     * @return array|bool 取得に成功した場合、ユーザ情報を返却。取得失敗の場合はfalseを返却
     */
    public function search(array $attributes, int $page = 1, int $limit = 10): array|bool
    {
        $andWheres = [];
        if (isset($attributes['and_where'])) {
            $andWheres = $attributes['and_where'];
        }
        $offset = ($page - 1) * $limit;
        return $this->select($andWheres, $offset, $limit);
    }

    /**
     * ユーザ認証
     *
     * @param string $email
     * @param string $password
     * @return array|bool 認証が問題なければユーザ情報を返却。認証失敗の場合はfalseを返却
     */
    public function authenticate(string $email, string $password): array|bool
    {
        // ユーザ情報取得
        $searchParam = [
            'and_where' => [
                'email' => ['keyword' => $email],
            ],
        ];
        $user = $this->search($searchParam, 1, 1);

        // ユーザ情報が取得できなければ処理終了
        if (!is_array($user) || empty($user)) {
            return false;
        }
        $user = $user[0];

        // パスワードチェック
        if (password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

    /**
     * ユーザ新規登録
     *
     * @param string $email
     * @param string $name
     * @param string $password
     * @param string $avatarId
     * @param ?bool $isAdmin
     * @return bool 登録成否
     */
    public function store(string $email, string $name, string $password, string $avatarId, ?bool $isAdmin): bool
    {
        $parameters = [
            'email' => $email,
            'name' => $name,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'avatar_id' => $avatarId,
            'is_admin' => $isAdmin ?? false,
        ];

        return $this->insert($parameters);
    }

    /**
     * ユーザ情報更新
     *
     * @param int $id
     * @param string $email
     * @param string $name
     * @param string $password
     * @param string $avatarId
     * @param ?bool $isAdmin
     * @return bool 更新成否
     */
    public function update(
        int $id,
        string $email,
        string $name,
        string $password,
        string $avatarId,
        ?bool $isAdmin
    ): bool {
        if (empty($name)) {
            return false;
        }

        $parameters = ['name' => $name];

        // メールアドレスは管理者の場合かつ自分以外のユーザの場合のみ変更可能
        if (!empty($email) && $_SESSION['user']['is_admin'] && $id !== $_SESSION['user']['id']) {
            $parameters['email'] = $email;
        }
        if (!empty($password)) {
            $parameters['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        if (!empty($avatarId)) {
            $parameters['avatar_id'] = $avatarId;
        }
        if (is_bool($isAdmin)) {
            $parameters['is_admin'] = $isAdmin;
        }

        $andWheres = [
            'id' => ['keyword' => $id],
        ];

        return $this->updateBase($parameters, $andWheres);
    }
}
