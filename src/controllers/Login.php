<?php

namespace app\controllers;

class Login
{
    public const USERNAME = 'user121';
    public const PASSWORD = '123Q';

    /**
     * @return void
     */
    public function login()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if ($_POST['username'] === self::USERNAME && $_POST['password'] === self::PASSWORD) {
                session_start();
                $_SESSION['login'] = true;

                header('Location: /');
            } else {
                require_once('assets.php');
                echo "Неверный пароль или логин \n";
                echo '<a class="btn btn-warning" href="/login.php">Попробовать еще раз</a>';
            }
        }
    }
}