<?php namespace Models;

class Device
{
    
    private $id;
    private $type;
    private $password;
    private $status;
    private $action;
    
    private $conn;
    
    public function __construct($id)
    {
        $this->conn = new Connection();
        $this->id = $id;
    }
    
    public function set($attr, $content)
    {
        $this->$attr = $content;
    }
    
    public function get($attr)
    {
        return $this->$attr;
    }
    
    public function validate($password)
    {
        $sql = "SELECT type, password FROM devices WHERE id = '{$this->id}'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        return $password == $row['password'];
    }
    
    public function getType()
    {
        $sql = "SELECT type FROM devices WHERE id = '{$this->id}'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        $this->type = $row['type'];
        return $this->type;
    }
    
    public function getStatus()
    {
        $sql = "SELECT status FROM control WHERE device = '{$this->id}'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        $this->status = $row['status'];
        return $this->status;
    }
    
    public function updateStatus($value)
    {
        $sql = "UPDATE control SET status = $value WHERE device = '{$this->id}'";
        $this->conn->simpleQuery($sql);
        $this->status = $value;
        /*
        if(self::getAction() != self::getStatus()) {
            sleep(1);
            if(self::getAction() != self::getStatus()) {
                self::act(self::getStatus());
            }
        }
        */
    }
    
    public function getAction()
    {
        $sql = "SELECT action FROM control WHERE device = '{$this->id}'";
        $result = $this->conn->returnQuery($sql);
        $row = mysqli_fetch_assoc($result);
        $this->action = $row['action'];
        return $this->action;
    }
    
    public function act($value)
    {
        $sql = "UPDATE control SET action = $value WHERE device = '{$this->id}'";
        $this->conn->simpleQuery($sql);
        $this->action = $value;
        /*
        if(self::getAction() != self::getStatus()) {
            sleep(100);
            if(self::getAction() != self::getStatus()) {
                self::act(self::getStatus());
            }
        }
        */
    }
}

?>