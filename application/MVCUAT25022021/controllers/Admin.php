<?php

    class Admin extends CI_Controller{
        private $user_id;
        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->user_id=$this->session->userdata('admin_id');
            $role = $this->session->userdata('role-PB');
            if(!empty($this->user_id)){
                redirect("Home");
            }
        }

        public function index(){
            $this->load->model('userValidation');
            $expiryObj = $this->userValidation->CheckExpiry();
            if(!empty($expiryObj)){
                $date1 = strtotime($expiryObj->StartDate);
                $years = $expiryObj->Years;
                $date2 = strtotime(date('Y-m-d'));
                // if($date2 >= $date1){
                //     $this->load->view("User/expired");
                // }
               // else{
                    $msg = $this->session->flashdata('msg');
                    $this->load->view("User/index", ["msg" => $msg]);
               // }
            }
            // $this->load->view("NavFoot/footer");
        }
        
    }
?>