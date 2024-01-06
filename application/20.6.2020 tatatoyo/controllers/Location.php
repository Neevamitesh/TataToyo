<?php
    class Location extends CI_Controller{
        public $admin_id;
        public $role;
        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->admin_id = $this->session->userdata('admin_id');
            $this->role = $this->session->userdata('role');
            if(empty($this->admin_id)){
                redirect("Admin");
            }
            $this->load->model('GetLocation');
            $this->load->model("GetUserData");
        }

        public function NavData(){
            $NavData =  array(
                'error'=>''
            );
            return $NavData;
        }

        private function Support($view,$data=[]){
            $NavData = $this->GetUserData->NavData();
            $data = array_merge_recursive($NavData,$data);
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }

        public function index($msg = null){
            $msg = $this->session->flashdata('msg');
            $location = $this->GetLocation->GetAllLocation();
            $view = "User/locationrmaster";
            $data = array(
                'page' => 'usermaster',
                'type' => '',
                'title' => 'Location Master',
                'msg' => $msg,
                'location' => $location,
                'role' => $this->role,
            );
            $this->Support($view, $data);
        }

        public function AddLocation(){
            $locationcode = $this->input->post('locationcode', true);
            $locationname = $this->input->post('locationname', true);
            $printername = $this->input->post('printername', true);
            $values = array($locationcode, $locationname);
            $res = $this->GetLocation->AddLocation($values);
            // print_r($res);
            $LOCID = $res->id;
            if($res->MESSAGE == "ok"){
                foreach($printername as $printer){
                    $PrinterMaster[] = array(
                        'LOCID' => $LOCID,
                        'PrinterName' => $printer

                    );
                }
                $res2 = $this->GetLocation->AddPrinter($PrinterMaster);
                if($res2 > 0){
                    $msg = "Location Added !";
                }
                else{
                    $msg = "Something went wrong !";
                }   
            }
            else{
                $msg = "Something went wrong !";
            }   
            $this->session->set_flashdata('msg',$msg);
            redirect('Location');
        }

        public function GetLocationJSON(){
            $LOCID = $_POST["locid"];
            $res = $this->GetLocation->GetGetLocationByID($LOCID);
            echo json_encode($res);
        }

        public function UpdateLocation(){
            $locationcode = $this->input->post('locationcode', true);
            $locationname = $this->input->post('locationname', true);
            $LOCID = $this->input->post('locid', true);
            $values = array($locationcode, $locationname, $LOCID);
            $res = $this->GetLocation->UpdateLocation($values);
            if($res->MESSAGE == "OK"){
                $msg = "Location Updated !";
            }
            else{
                $msg = "Something went wrong !";
            }   
            $this->session->set_flashdata('msg',$msg);
            redirect('Location');
            // print_r($res);
        }

        public function DeleteLocation(){
            $LOCID = $_POST["locid"];
            $this->load->model('GetLocation');
            $result=$this->GetLocation->DeleteLocation($LOCID);
            if($result){
                $msg = "Location Deleted !";
            }
            else{
                $msg = "Something went wrong !";
            }
            $this->session->set_flashdata('msg',$msg);
            redirect('Location');
        }

    }
?>