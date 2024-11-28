<?php

namespace App\Controllers;

use App\Models\User;

class LoginController extends Controller
{
    /**
     * ログイン画面を表示
     */
    public function index(): void
    {
        $this->view('login');
    }
}
