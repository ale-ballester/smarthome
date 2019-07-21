<?php namespace Controllers;

use Models\Device as Device;

class devicesController
{
    
    private $device;
    
    public function __construct()
    {
        $this->device = new Device($_POST['id']);
    }
    
    public function index()
    {
        $verify = $this->device->validate($_POST['password']);
        if($verify) {
            $this->device->updateStatus(intval($_POST['value']));
            // This shouldnt be all here
            // Here
            // If led is actioned manually
            // $this->device->act(intval($_POST['value']));
            // Not here
            // If it is actioned from web, wait for the status to normalize for 5 seconds.
            // If it do not normalize.
            // $this->device->getAction(intval($_POST['value']));
            $data = '<' . $this->device->getAction() . '>';
            return $data;
        } else {
            $data = '<b>';
            return $data;
        }
    }
}

?>