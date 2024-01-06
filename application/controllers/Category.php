<?php
    class Category extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $admin_id=$this->session->userdata('admin_id');
            if(empty($admin_id)){
                redirect('Admin');
            }
            $this->load->model("GetCategory");
        }
        public function NavData(){
            $NavData =  array(
                'error'=>''
            );
            return $NavData;
        }

        private function Support($view,$data=[]){
            $NavData = $this->NavData();
            $data = array_merge_recursive($NavData,$data);
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }
        public function index($msg = null){
            $category=$this->GetCategory->Category();
            $msg = $this->session->flashdata('msg');
            $view = "User/category";
            $data = array(
                'page' => 'product',
                'type' => 'category',
                'title' => 'Category',
                'msg' => $msg,
                'category' => $category,
            );
            $this->Support($view, $data);
        }
        public function UploadCategory(){
            $category = $this->input->post('category', true);
            $data = array(
                'Category_Name' => $category
            );
            $res = $this->GetCategory->UploadCategory($data);
            if($res){
                $msg = "Category Added !";
            }
            else{
                $msg = "Something went wrong !";
            }
            $this->session->set_flashdata('msg',$msg);
            redirect('Category');
        }
        public function GetCategoryJSON(){
            $CID = $_POST["cid"];
            $res = $this->GetCategory->GetCategoryByCID($CID);
            echo json_encode($res);
        }
        public function UpdateCategory(){
            $category = $this->input->post('category', true);
            $cid = $this->input->post('cid', true);
            $Msg = "";
            $data = array(
                'Category_Name' => $category
            );
            $res = $this->GetCategory->UpdateCategory($data, $cid);
            if($res){
                $msg = "Category Updated !";
            }
            else{
                $msg = "Something went wrong !";
            }
            $this->session->set_flashdata('msg',$msg);
            redirect('Category');
        }

        public function DeleteCategory(){
            $CID = $_POST["cid"];
            $this->load->model('GetCategory');
            $result=$this->GetCategory->DeleteCategory($CID);
            if($result){
                $msg = "Category Deleted !";
            }
            else{
                $msg = "Something went wrong !";
            }
            $this->session->set_flashdata('msg',$msg);
            redirect('Category');
        }
        
    }
?>