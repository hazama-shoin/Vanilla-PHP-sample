<?php

namespace App\Controllers;

class RegisterController extends Controller
{
    /**
     * 会員登録画面を表示
     */
    public function index(): void
    {
        $isLogined = isset($_SESSION['user']);
        $this->view('register', ['isLogined' => $isLogined]);
    }
}
