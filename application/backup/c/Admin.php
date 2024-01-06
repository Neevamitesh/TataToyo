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
            $msg = $this->session->flashdata('msg');
            $this->load->view("User/index", ["msg" => $msg]);
            // $this->load->view("NavFoot/footer");
        }

               
        
    }
?>