<?php namespace Models;

class Connection
{
    
    private $data = array(
        'host' => 'localhost',
        'user' => 'ale_ballester',
        'pass' => '',
        'db' => 'domo',
    );
    
    private $con;
    
    public function __construct()
    {
        $this->con = new \mysqli($this->data['host'], $this->data['user'], $this->data['pass'], $this->data['db']);
        if($this->con->connect_errno > 0) {
            die('Unable to connect to database [' . $this->con->connect_error . ']');
        }
    }
    
    public function simpleQuery($sql)
    {
        $this->con->query($sql);
    }
    
    public function returnQuery($sql)
    {
        $result = $this->con->query($sql);
        return $result;
    }
}

?>