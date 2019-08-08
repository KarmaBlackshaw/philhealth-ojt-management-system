<?php 

require_once dirname(__DIR__) . '/lib/init.php';

if (isset($_POST['login'])) {
    $username = $init->inject($_POST['username']);
    $password = $init->inject($_POST['password']);

    if(empty($username) || empty($password)){
        header('Location: ' . explode('?', $_SERVER['HTTP_REFERER'])[0] . '?error=1');
        exit();
    } else{
        $sql = $init->getQuery("SELECT *, COUNT(*) x FROM users WHERE username = '$username' AND removed = 0");

        if($sql[0]->x > 0) {

            $name = fullname($sql[0]->name);
            $position = $sql[0]->position;
            $user_id = $sql[0]->user_id;
            $hash = $sql[0]->password;

            if (password_verify($password, $hash)) {
                $_SESSION['login'] = true;
                $_SESSION['name'] = $name;
                $_SESSION['user_id'] = $user_id;
                
                $init->audit('Logged in', '0');

                // echo abs_views('index');
                header('Location: ' . rel_views('index'));
                exit();
            } else {
                header('Location: ' . explode('?', $_SERVER['HTTP_REFERER'])[0] . '?error=2');
                exit();
            }
        } else {
            header('Location: ' . explode('?', $_SERVER['HTTP_REFERER'])[0] . '?error=3');
            exit();
        }
    }
}

if (isset($_POST['logout'])) {
    if (isset($_SESSION['user_id'])) {
        $sql = $init->audit("Logged out", 0);
        session_start();
        unset($_SESSION['user_id']);
        session_destroy();
        header("Location: ../");
        exit;
    } else {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../");
        exit;
    }
}
