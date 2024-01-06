<?php
    class Preview extends CI_Controller{
        private $DateTime,$user_id,$Msg=array(
            "Msg"=>"Something Went Wrong",
            "Res" => false
        );
        public function __construct(){
            parent::__construct();
            $this->user_id = $this->session->userdata('admin_id');
            !empty($this->user_id)?:redirect("Admin");
            $this->load->model("GetUserData","GD");
            $this->load->model("ConTran","CT");
            $this->load->model("AddOnsTran","AT");
            date_default_timezone_set("Asia/Kolkata");
            $this->DateTime = date('Y-m-d H:i:s');
            $this->load->model("PreTran","PT");
        }
        private function GetParam($Type="GET",$ID = 0){
            $Name = $this->input->post('Name',true);
            $Code = $this->input->post('Code',true);
            $Address = $this->input->post('Address',true);
            $State = $this->input->post('State',true);
            $City = $this->input->post('City',true);
            $PinCode = $this->input->post('PinCode',true);
            $Contact = $this->input->post('Contact',true);
            $MDID = $this->input->post('Date',true);
            $SRNO = $this->input->post('IndexLen',true);
            $SRAuto = ($this->input->post('SrAuto',true) == 'yes') ? 1 : 0;
            return $Master = array(
                $Type,$ID,$Code,$Name,$Address,
                $Contact,$City,$State,$PinCode,"India",
                $MDID,$SRNO,$SRAuto,$this->user_id,"NULL",
                "NULL","NULL","NULL","NULL","NULL",
            );
        }
        private function Support($view,$data=[]){
            $NavFoot = $this->GD->NavData();
            $data = array_merge_recursive($NavFoot,$data);
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }
        public function index(){
            $Consignee = $this->PT->GetNewConsignee();
            $Data = array(
                "OurConsignee" => $Consignee,
                "title" => "Add Consignee Preview File"
            );
            $this->Support("Preview/index",$Data);
        }
        public function RepalceFile(){
            $Consignee = $this->PT->Available();
            $Data = array(
                "OurConsignee" => $Consignee,
                "title" => "Replace Consignee PRN File"
            );
            $this->Support("Preview/Edit",$Data);
        }
        public function AddPreview(){
            $CMID = $this->input->post('Consignee',true); # CMID
            is_dir('upload/preview/')?:mkdir('upload/preview/');
            is_dir('upload/preview/'.$this->user_id)?:mkdir('upload/preview/'.$this->user_id);            
            $config = array(
                "upload_path" => "upload/preview/{$this->user_id}/",
                'allowed_types' => "*"
            );
            
            $this->load->library("upload",$config);
            $this->upload->do_upload('PRNFile');
            $Result = $this->upload->data();
            $Path = "upload/preview/{$this->user_id}/{$Result['file_name']}";
            

            $this->upload->do_upload('Attach');
            $Result = $this->upload->data();
            $Attach = "upload/preview/{$this->user_id}/{$Result['file_name']}";
            
            $Res = $this->PT->UploadFile(array(
                "CMID" => $CMID,
                "Path" => $Path,
                "Image" => $Attach
            )); 
            $this->session->set_flashdata("Lab-File-Pre",array(
                "Res" => ($Res > 0),
                "Msg" => "Consignee Label Preview File Uploaded Successfully"
            ));
            redirect("Preview");
        }
    }
    
?>