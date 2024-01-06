<?php
    class Home extends CI_Controller{
        public $role;
        public $admin_id;
        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->admin_id = $this->session->userdata('admin_id');
            $this->role = $this->session->userdata('role');
            $this->load->model("GetUserData","GD");
            !empty($this->admin_id)?:redirect("Admin");
            $this->load->model('GetReports');
        }

        private function Support($view,$data=[]){
            $NavData = $this->GD->NavData();
            $data = array_merge_recursive($NavData,$data);
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }

        public function index(){
            $view = "User/dashboard";
            $consigneeCount = $this->GetReports->ConsigneeCount();
            $productCount = $this->GetReports->ProductCount();
            $msg = $this->session->flashdata('msg');
            $data = array(
                'page' => 'dashboard',
                'type' => '',
                'title' => 'Dashboard',
                'msg' => $msg,
                'consigneecount' => $consigneeCount,
                'productcount' => $productCount,
                'role' => $this->role,
            );
            $this->Support($view, $data);
        }

    }
?>