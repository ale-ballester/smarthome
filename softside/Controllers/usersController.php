<?php namespace Controllers;

use Models\User as User;
use Models\Device as Device;

class usersController
{
    
    private $user;
    private $devices;
    
    public function __construct()
    {
        $this->user = new User();
    }
    
    public function index()
    {
        if ($_POST) {
            session_start();
            $username = $_POST['username'];
            $password = $_POST['password'];
            $verify = $this->user->validate($username, $password);
            if($verify) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['name'] = $verify['name'];
                $_SESSION['email'] = $verify['email'];
                header('Location: ' . URL . 'users' . DS . 'control' . DS);
                die();
            } else {
                echo 'Username or password incorrect';
            }
        }
    }
    
    public function register()
    {
        if($_POST){
            if($_POST['pass1'] == $_POST['pass2']) {
                if($this->user->register($_POST['username'], $_POST['name'], $_POST['email'], $_POST['pass1'])) {
                    $data = 1;
                    return $data;
                } else {
                    $data = 2;
                    return $data;
                }
            } else {
                $data = 3;
                return $data;
            }
        }
    }
    
    public function control()
    {
        session_start();
        if(empty($_SESSION)) {
            header('Location: ' . URL . 'users' . DS . 'index' . DS);
            die();
        }
        $this->user->set('id', $_SESSION['username']);
        $this->user->set('password', $_SESSION['password']);
        $this->user->set('name', $_SESSION['name']);
        $this->user->set('email', $_SESSION['email']);
        $devs = $this->user->getDevices();
        $devs['obj'] = array();
        $num = sizeof($devs['id']);
        for($i = 0; $i < $num; $i++) {
            $devs['obj'][$i] = new Device($devs['id'][$i]);
        }
        for($i = 0; $i < $num; $i++) {
            $devs['type'][$i] = $devs['obj'][$i]->getType();
        }
        $this->devices = $devs;
        $data = $this->devices;
        return $data;
    }
    
    public function adddev()
    {
        if($_POST) {
            session_start();
            $this->user->set('id', $_SESSION['username']);
            if($this->user->nameDevice($_POST['id'], $_POST['code'], $_POST['name'])) {
                header('Location: ' . URL . 'users' . DS . 'control' . DS);
                die();    
            } else {
                $data = 'Device ' . $_POST['id'] . ' does not exist or id does not match code';
                return $data;
            }
        }
    }
    
    public function settings()
    {
        if($_POST) {
            session_start();
            $this->user->set('id', $_SESSION['username']);
            if($_POST['oldpass']) {
                if($_POST['newpass1'] == $_POST['newpass2']) {
                    if($_POST['oldpass'] == $this->user->getPass()) {
                        $this->user->changePassword($_POST['newpass1']);
                        $data = 2;
                        return $data;
                    } else {
                        $data = 3;
                        return $data;
                    }
                } else {
                    $data = 1;
                    return $data;
                }
            }
        }
    }
    
    public function logout()
    {
        session_start();
        $_SESSION = array();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        if(session_destroy() && !isset($_SESSION)) {
            header('Location: ' . URL . 'users' . DS . 'index' . DS);
            die();
        }
    }
    
    public function sync()
    {
        self::control();
        $num = sizeof($this->devices['id']);
        $data = '';
        for($i = 0; $i < $num; $i++) {
            $data = $data . $this->devices['id'][$i] . '.' . $this->devices['type'][$i] . '=';
            $data = $data . $this->devices['obj'][$i]->getStatus() . '\\';
        }
        return $data;
    }
    
    public function act()
    {
        self::control();
        $num = sizeof($this->devices['id']);
        for($i = 0; $i < $num; $i++) {
            if($this->devices['id'][$i] == $_POST['device']) {
                if($this->devices['type'][$i] == 'digitalout') {
                    if($this->devices['obj'][$i]->getAction() == 255) {
                        $this->devices['obj'][$i]->act(0);
                        break;       
                    } elseif($this->devices['obj'][$i]->getAction() == 0) {
                        $this->devices['obj'][$i]->act(255);
                        break;    
                    }
                }
            }
        }
    }
}

?>