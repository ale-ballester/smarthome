<?php namespace Models;

class User
{
    
    private $id;
    private $name;
    private $password;
    private $email;
    private $devices = array(
        'id' => array(),
        'type' => array(),
        'name' => array(),
        'section' => array(),
        'status' => array(),
        'action' => array(),
    );
    
    private $conn;
    
    public function __construct()
    {
        $this->conn = new Connection();
    }
    
    public function set($attr, $content)
    {
        $this->$attr = $content;
    }
    
    public function get($attr)
    {
        return $this->$attr;
    }
    
    public function register($id, $name, $email, $password)
    {
        $sql = "SELECT id FROM users WHERE id = '$id'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        if(!$row['id']) {
            $sql = "INSERT INTO users (id, name, email, password) VALUES ('$id', '$name',  '$email', '$password')";
            $this->conn->simpleQuery($sql);
            return true;
        }
        return false;
    }
    
    public function validate($user, $pass)
    {
        $sql = "SELECT name, email, password FROM users WHERE id = '$user'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['password'] == $pass) {
            $this->id = $user;
            $this->password = $pass;
            $this->name = $row['name'];
            $this->email = $row['email'];
            return array(
                'id' => $this->id,
                'password' => $this->password,
                'name' => $this->name,
                'email' => $this->email,
            );
        }
        return false;
    }
    
    public function getPass()
    {
        $sql = "SELECT password FROM users WHERE id = '{$this->id}'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        return $row['password'];
    }
    
    public function changePassword($pass)
    {
        $sql = "UPDATE users SET password = '$pass' WHERE id = '{$this->id}'";
        $this->conn->simpleQuery($sql);
    }
    
    public function getDevices(){
        $sql = "SELECT device, name FROM userdev WHERE user = '{$this->id}'";
        $result = $this->conn->returnQuery($sql);
        for($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
            $this->devices['id'][$i] = $row['device'];
            $this->devices['name'][$i] = $row['name'];
        }
        return $this->devices;
    }
    
    public function nameDevice($id, $code, $name)
    {
        $sql = "SELECT id, code FROM devices WHERE id = '$id'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['id'] && $row['code'] == $code) {
            $sql = "INSERT INTO userdev (user, device, name) VALUES ('{$this->id}', '$id', '$name')";
            $this->conn->simpleQuery($sql);
            return true;
        }
        return false;
    }
}

?>