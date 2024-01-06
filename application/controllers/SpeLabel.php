<?php
    class SpeLabel extends CI_Controller{
        private $DateTime,$user_id;
        public function __construct(){
            parent::__construct();
            $this->user_id = $this->session->userdata('admin_id');
            !empty($this->user_id)?:redirect("Admin");
            $this->load->model('SpeLabelTran',"SL");
            $this->load->model("GetUserData","GD");
            $this->load->model("ConTran","CT");
            date_default_timezone_set("Asia/Kolkata");
            $this->DateTime = date('Y-m-d H:i:s');
        }
        private function Support($view,$data=[]){
            $NavFoot = $this->GD->NavData();
            $data = array_merge_recursive($NavFoot,$data);
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }
        public function index(){
            $Consignee = $this->SL->Consignee();
            $Data = array(
                "OurConsignee" => $Consignee,
                "title" => "Add Consignee Additional PRN File"
            );
            $this->Support("AddLabel/PRNUpload",$Data);
        }
        public function PRNEdit(){
            $Consignee = $this->CT->Consignee($this->GetParam("GetWithFile"));
            $Data = array(
                "OurConsignee" => $Consignee,
                "title" => "Replace Consignee PRN File"
            );
            $this->Support("Label/PRNEdit",$Data);
        }
        public function AddPRNFile(){
            $CMID = $this->input->post('Consignee',true); # CMID
            $Printer = $this->input->post('PrinterDpi',true); # PrinterDpi
            $CreatedAt = $this->DateTime;
            $CreatedBy = $this->user_id;
            $Upd = $this->input->post("Update",true);
            is_dir('upload/')?:mkdir('upload/');
            is_dir('upload/Additional')?:mkdir('upload/Additional');
            is_dir('upload/Additional/'.$this->user_id)?:mkdir('upload/Additional/'.$this->user_id);            
            $config = array(
                "upload_path" => "upload/Additional/{$this->user_id}/",
                'allowed_types' => "*"
            );
            $this->load->library("upload",$config);
            $this->upload->do_upload('PRNFile');
            $Result = $this->upload->data();
            $Path = "upload/Additional/{$this->user_id}/{$Result['file_name']}";
            // $this->SL->AddLabel($CMID,[
            //         "Path" => $Path,
            //         "CMID" => $CMID,
            //         "CreatedAt" => $this->DateTime,
            //         "CreatedBy" => $this->user_id
            //     ]);
            $this->SL->AddLabel(array(
                "INS",$CMID,$Path,$CreatedAt,$CreatedBy,$Printer
            ));
            $Page = empty($Upd) ? "Add" : "Edit"; 
            $this->session->set_flashdata("Lab-File-{$Page}",array(
                "Res" => true,
                "Msg" => "Consignee Additional Label File Uploaded Successfully"
            ));
            $Upd = empty($Upd) ? "index" : "Edit";
            redirect("SpeLabel/$Upd");
        }
        public function UpdatePRNFile(){
            $CMID = $this->input->post('Consignee',true); # CMID
            $Printer = $this->input->post('PrinterDpi',true); # PrinterDpi
            $CreatedAt = $this->DateTime;
            $CreatedBy = $this->user_id;
            $Upd = $this->input->post("Update",true);
            is_dir('upload/')?:mkdir('upload/');
            is_dir('upload/Additional')?:mkdir('upload/Additional');
            is_dir('upload/Additional/'.$this->user_id)?:mkdir('upload/Additional/'.$this->user_id);            
            $config = array(
                "upload_path" => "upload/Additional/{$this->user_id}/",
                'allowed_types' => "*"
            );
            $this->load->library("upload",$config);
            $this->upload->do_upload('PRNFile');
            $Result = $this->upload->data();
            $Path = "upload/Additional/{$this->user_id}/{$Result['file_name']}";
            // $this->SL->AddLabel($CMID,[
            //         "Path" => $Path,
            //         "CMID" => $CMID,
            //         "CreatedAt" => $this->DateTime,
            //         "CreatedBy" => $this->user_id
            //     ]);
            $this->SL->AddLabel(array(
                "UPD",$CMID,$Path,$CreatedAt,$CreatedBy,$Printer
            ));
            $Page = empty($Upd) ? "Add" : "Edit"; 
            $this->session->set_flashdata("Lab-File-{$Page}",array(
                "Res" => true,
                "Msg" => "Consignee Additional Label File Uploaded Successfully"
            ));
            $Upd = empty($Upd) ? "index" : "Edit";
            redirect("SpeLabel/$Upd");
        }
        public function Edit(){
            $Consignee = $this->SL->GetLabel();
            $Data = array(
                "OurConsignee" => $Consignee,
                "title" => "Replace Additional Consignee PRN File"
            );
            $this->Support("AddLabel/PRNEdit",$Data);
        }
    }
?>